<?php

namespace App\Http\Controllers;

use App\Services\Register\RegisterService;
use Exception;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        private readonly RegisterService $registerService
    ){}

    /**
     * @throws Exception
     */
    public function registerTeam(Request $request): void
    {
        $request->validate([
            'olympiadId' => 'required|integer',
            'registrationForm' => 'array'
        ]);
        $this->registerService->registerTeam($request->registrationForm, $request->olympiadId);
    }

    /**
     * @throws Exception
     */
    public function verifyTeam(string $key): string
    {
        dd(1);
        return $this->registerService->verifyTeam($key);
    }
}
