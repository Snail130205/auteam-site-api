<?php

namespace App\Models;

use DateTime;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OlympiadTypes extends Model
{
    use HasFactory;

    public function getStartOlympiadDate(): DateTime
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $this->startOlympiadDate);
    }
}
