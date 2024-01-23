<?php

namespace App\Dto;


use Illuminate\Support\Facades\Request;

class RegistrationEmailDto
{
    public function __construct(string $email, string $hash, string $olympiadName, string $teamName)
    {
        $this->email = $email;
        $this->olympiadName = $olympiadName;
        $this->teamName = $teamName;
        $this->registrationUrl = 'https://' . Request::server('HTTP_HOST') . '/api/verified/' . $hash;
    }

    /**
     * Название олимпиады
     * @var string
     */
    public string $olympiadName;

    /**
     * Название команды
     * @var string
     */
    public string $teamName;

    /**
     * Почта, кому отправить сообщение
     * @var string
     */
    public string $email;

    /**
     * Ссылка для подтверждения регистрации
     * @var string
     */
    public string $registrationUrl;

    /**
     *
     * @var ParticipantLoginDataDto[]
     */
    public array $participants = [];
}

