<?php

namespace App\Services\Dictionary;

use App\Models\EducationType;

class DictionaryService
{
    public function getEducationType(): array
    {
        return EducationType::get()->toArray();
    }
}
