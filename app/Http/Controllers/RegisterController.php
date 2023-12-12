<?php

namespace App\Http\Controllers;

use App\Services\Register\RegisterService;
use Exception;
use Illuminate\Http\Request;

/**
 * RegisterController
 * Контроллер отвечает за регистрацию форм, подтвержджение регистрацию
 */
class RegisterController extends Controller
{
    public function __construct(
        private readonly RegisterService $registerService
    ){}

    /**
     * Функция регистрации команды
     * @param Request $request
     * @return true
     * @throws Exception
     */
    public function registerTeam(Request $request): bool
    {
        $request->validate([
            'olympiadId' => 'required|integer',
            'registrationForm' => 'array'
        ]);
        $this->registerService->registerTeam($request->registrationForm, $request->olympiadId);
        return true;
    }

    /**
     * Функция подтверждения регистрации
     * @param string $key - hash по которому подтверждается регистрация
     * @return string
     * @throws Exception
     */
    public function verifyTeam(string $key): string
    {
        return $this->registerService->verifyTeam($key);
    }
}
