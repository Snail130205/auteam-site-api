<?php

namespace App\Dto;

class OlympiadDto
{
    public function __construct(int $teamCount, \DateTime $olympiadStartDate)
    {
        $this->teamCount = $teamCount;

        $this->olympiadStartDate = $olympiadStartDate;
    }

    public int $teamCount;

    public \DateTime $olympiadStartDate;
}
