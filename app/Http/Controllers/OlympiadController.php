<?php

namespace App\Http\Controllers;

use App\Dto\OlympiadDto;
use App\Services\Olympiad\OlympiadService;
use Exception;
use Illuminate\Http\JsonResponse;

class OlympiadController extends Controller
{
    public function __construct(
        private readonly OlympiadService $olympiadService
    ){}

    /**
     * Получение данных об олимпиаде
     * @param int $olympiadId
     * @return JsonResponse
     * @throws Exception
     */
    public function getOlympiadInfo(int $olympiadId): JsonResponse
    {
        return response()->json($this->olympiadService->getOlympiadInfo($olympiadId));
    }
}
