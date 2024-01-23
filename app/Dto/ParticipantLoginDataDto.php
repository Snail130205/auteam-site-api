<?php

namespace App\Dto;

use Illuminate\Support\Str;

class ParticipantLoginDataDto
{
    public function __construct(string $name, string $login){
        $this->name = $name;
        $this->login = $login;
        $this->password = Str::random(11);
    }

    /**
     * Имя зарегистрированного пользователя
     * @var string
     */
    public string $name;

    /**
     * Логин пользователя
     * @var string
     */
    public string $login;

    /**
     * Пароль пользователя
     * @var string
     */
    public string $password;
}
