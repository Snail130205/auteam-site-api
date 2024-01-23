<?php

namespace App\Http\Controllers;

use App\Services\Yandex\YandexService;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function __construct(
        private readonly YandexService $yandexService
    ){}

    public function acceptCaptchaResult(Request $request)
    {
        $request->validate([
            'token' => 'string'
        ]);
        return response()->json(['success' => $this->yandexService->acceptCaptchaResult($request->token)]);
    }
}
