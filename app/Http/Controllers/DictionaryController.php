<?php

namespace App\Http\Controllers;

use App\Services\Dictionary\DictionaryService;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    public function __construct(
        private readonly DictionaryService $dictionaryService
    ){}

    public function getEducationType(): array
    {
        return $this->dictionaryService->getEducationType();
    }
}
