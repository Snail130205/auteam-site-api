<?php

namespace App\Exceptions;

use Throwable;

class UserValidateException extends \Exception
{
    public function __construct(string $message = "Вы неверно заполнели поля", int $code = 401, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
