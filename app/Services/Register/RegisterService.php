<?php

namespace App\Services\Register;

use App\Dto\ParticipantLoginDataDto;
use App\Dto\RegistrationEmailDto;
use App\Exceptions\UserValidateException;
use App\Mail\RegistrationMail;
use App\Models\EducationInstitutions;
use App\Models\OlympiadTypes;
use App\Models\Participants;
use App\Models\RegistrationFormDictionary;
use App\Models\TeamLeaders;
use App\Models\Teams;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class RegisterService
{
    /**
     * Регистрация команды по форме
     * @param array $registrationForm форма регистрации
     * @param int $olympiadId идентификатор олимпиады
     * @return void
     * @throws Exception
     */
    public function registerTeam(array $registrationForm, int $olympiadId): void
    {
        $olympiad = OlympiadTypes::find($olympiadId);
        if (!isset($olympiad)) {
            throw new UserValidateException('Не существует олимиады для регистрации!');
        }

        $tables = $this->getRegistrationModels($registrationForm, $olympiadId);

        try {
            DB::beginTransaction();
            $teamLeaderModel = TeamLeaders::firstOrCreate($tables['team_leaders']->getAttributes());
            $educationInstitutionModel = EducationInstitutions::firstOrCreate($tables['education_institutions']->getAttributes());
            $teamModel = $this->registerTeamModel($tables['teams'], $teamLeaderModel->id, $educationInstitutionModel->id, $olympiadId);
            $registrationEmailDto = new RegistrationEmailDto($teamLeaderModel->email, $teamModel->registerHash, $olympiad->name, $teamModel->name);
            $registrationEmailDto->participants = $this->registerParticipants($tables['participants'], $teamModel);
            Mail::to($registrationEmailDto->email)->send(new RegistrationMail($registrationEmailDto));
            DB::commit();
        } catch (UserValidateException $validateException) {
            DB::rollback();
            Log::channel('daily')->error($validateException->getMessage());
            throw new UserValidateException($validateException->getMessage());
        } catch (Exception $exception) {
            DB::rollback();
            Log::channel('daily')->error($exception->getMessage());
            throw new Exception('Произошла ошибка при создании команды. Обратитесь к администратору для решение вашей пробелемы!');
        }
    }

    /**
     * Подтверждение регистрации команды
     * @param string $teamKey
     * @return string
     * @throws Exception
     */
    public function verifyTeam(string $teamKey): string
    {
        $team = (new Teams())
            ->where('registerHash', $teamKey)
            ->where('isRegister', false)
            ->first();
        if (!isset($team)) {
            throw new UserValidateException('Неверная ссылка или ссылка устарела!');
        }
        $team->isRegister = true;
        try {
            DB::beginTransaction();
            $team->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
            Log::channel('daily')->error($exception->getMessage());
            throw new Exception('Произошла ошибка при регистрации команды! Пожалуйста обратитесь к администратору');
        }

        return 'Успешно';
    }

    public function getRegistrationModels(array $registrationForm, int $olympiadId): array
    {
        $dictionaryFields = (new RegistrationFormDictionary())
            ->where('olympiadTypeId', $olympiadId)
            ->get()
        ;
        if ($dictionaryFields->count() !== count($registrationForm)) {
            throw new UserValidateException('Неверно заполнены поля!');
        }
        $tables = [
            'team_leaders' => new TeamLeaders(),
            'education_institutions' => new EducationInstitutions(),
            'teams' => new Teams(),
            'participants' => []
        ];
        $userDataToCheckValidate = [];
        $dictionaryFields->each(function (RegistrationFormDictionary $dictionaryField) use ($registrationForm, &$tables, &$userDataToCheckValidate) {
            $this->fillModelFromFrontend($dictionaryField, $registrationForm, $tables, $userDataToCheckValidate);
        });

        $this->validateRepeatInformation($userDataToCheckValidate, $olympiadId);

        return $tables;
    }

    /**
     * Обработка поле формы с фронта по шаблону бд
     * @param RegistrationFormDictionary $dictionaryField
     * @param array $registrationForm
     * @param array $tables
     * @return void
     * @throws Exception
     */
    private function fillModelFromFrontend(RegistrationFormDictionary $dictionaryField, array $registrationForm, array &$tables, array &$userDataToCheckValidate): void
    {
        if (!isset($registrationForm[$dictionaryField->name]) || empty($registrationForm[$dictionaryField->name])) {
            throw new UserValidateException('Неверно заполнены поля!');
        }
        $table = $dictionaryField->table;
        $fieldName = $dictionaryField->fieldName;

        /**
         *  Проверка валидности номеров телефона
         */
        if ($fieldName === 'phone') {
            $registrationForm[$dictionaryField->name] = $this->validatePhone($registrationForm[$dictionaryField->name]);
        }

        switch ($table) {
            case 'teams':
            case 'education_institutions':
            case 'team_leaders' :
                $tables[$table]->$fieldName = $registrationForm[$dictionaryField->name];
                break;
            case 'participants' :
                if (!isset($tables[$table][$dictionaryField->number])) {
                    $tables[$table][$dictionaryField->number] = new Participants();
                }
                if (in_array($fieldName, ['phone', 'email', 'fullName'])) {
                    $userDataToCheckValidate[$fieldName] = $userDataToCheckValidate[$fieldName] ?? [];
                    $userDataToCheckValidate[$fieldName][] = $registrationForm[$dictionaryField->name];
                }

                $tables[$table][$dictionaryField->number]->$fieldName = $registrationForm[$dictionaryField->name];
                break;
            default:
                throw new UserValidateException('Неверно заполнены поля!');
                break;
        }
    }

    /**
     * Регистрация команды
     * @param Teams $teamModel
     * @param int $teamLeaderId
     * @param int $educationalInstitutionId
     * @param int $olympiadTypeId
     * @return Teams
     * @throws Exception
     */
    private function registerTeamModel(Teams $teamModel, int $teamLeaderId, int $educationalInstitutionId, int $olympiadTypeId): Teams
    {
        $teamExists = (new Teams())
            ->where('olympiadTypeId', $olympiadTypeId)
            ->where('name', $teamModel->name)
            ->exists();
        if ($teamExists) {
            throw new UserValidateException('Не удалось создать команду! Такое название уже существует!');
        }
        $teamModel->teamLeaderId = $teamLeaderId;
        $teamModel->educationalInstitutionId = $educationalInstitutionId;
        $teamModel->olympiadTypeId = $olympiadTypeId;
        do {
            $hash = hash('sha512', $teamModel->name . time() . config('hash.salt'));
            $hashExist = (new Teams())
                ->where('registerHash', $hash)
                ->exists();
        } while ($hashExist);
        $teamModel->registerHash = $hash;
        $teamModel->save();

        return $teamModel;
    }

    /**
     * Регистрация участников
     * @param Participants[] $participants
     * @param Teams $team
     * @return ParticipantLoginDataDto[]
     */
    private function registerParticipants(array $participants, Teams $team): array
    {
        $participantLoginData = [];
        foreach ($participants as $key => $participant) {
            $login = str_replace(' ', '_', $team->name) . '_' . ($key + 1);
            $participantLoginDataDto = new ParticipantLoginDataDto($participant->fullName, $login);
            $participant->login = $participantLoginDataDto->login;
            $participant->password = hash('sha256', $participantLoginDataDto->password . config('hash.salt'));
            $participant->teamId = $team->id;
            $participant->save();
            $participantLoginData[] = $participantLoginDataDto;
        }

        return $participantLoginData;
    }

    private function validatePhone(string $phone): string
    {
        if (!preg_match('/^[+,8]/', $phone)) {
            throw new UserValidateException('Номера телефонов не соответствуют формату');
        }

        $phone = $phone[0] === '8' ? '+7' . substr($phone, 1) : $phone;

        if (!preg_match('/^(\+)?((\d{2,3}) ?\d|\d)(([ -]?\d)|( ?(\d{2,3}) ?)){5,12}\d$/', $phone)) {
            throw new UserValidateException('Номера телефонов не соответствуют формату');
        }

        return '+7' . preg_replace('/\D/', '', $phone);
    }

    private function validateRepeatInformation(array $userDataToCheckValidate, int $olympiadId): void
    {
        $teamIds = (new Teams())
            ->where('olympiadTypeId', $olympiadId)
            ->pluck('id')->toArray();
        ;
        $participantName = (new Participants())->where('teamId', $teamIds)->where('fullName', $userDataToCheckValidate['fullName'])->count();
        $participantEmail = (new Participants())->where('teamId', $teamIds)->where('email', $userDataToCheckValidate['email'])->count();
        $participantPhone = (new Participants())->where('teamId', $teamIds)->where('phone', $userDataToCheckValidate['phone'])->count();
        $errors = [];

        if (!empty($participantName)) {
            $errors[] = 'Пользователь/и с такими ФИО существует';
        }
        if (!empty($participantEmail)) {
            $errors[] = 'Пользователь/и с такой почтой существует';
        }
        if (!empty($participantPhone)) {
            $errors[] = 'Пользователь/и с такими номером существует';
        }

        if (!empty($errors)) {
            throw new UserValidateException(implode('\n', $errors));
        }
    }
}
