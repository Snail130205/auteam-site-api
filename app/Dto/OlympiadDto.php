<?php

namespace App\Dto;

class OlympiadDto
{
    public function __construct(int $teamCount, \DateTime $olympiadStartDate)
    {
        $this->teamCount = $teamCount;

        $this->olympiadStartDate = $olympiadStartDate->format('Y/m/d');
    }

    public int $teamCount;

    public ?string $olympiadStartDate;
}
