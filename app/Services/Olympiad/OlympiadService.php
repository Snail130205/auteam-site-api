<?php

namespace App\Services\Olympiad;

use App\Dto\OlympiadDto;
use App\Models\OlympiadTypes;
use App\Models\Teams;

class OlympiadService
{
    /**
     * Получение информации о Олимпиаде
     * @param int $olympiadId
     * @return OlympiadDto
     * @throws \Exception
     */
    public function getOlympiadInfo(int $olympiadId): OlympiadDto
    {
        $olympiad = OlympiadTypes::find($olympiadId);
        if (!isset($olympiad)) {
            throw new \Exception('Не существует олимиады для регистрации!');
        }

        $teamCount = (new Teams())
            ->where('olympiadTypeId', $olympiadId)
            ->get()
            ->count()
        ;

        return new OlympiadDto($teamCount, $olympiad->getStartOlympiadDate());
    }
}
