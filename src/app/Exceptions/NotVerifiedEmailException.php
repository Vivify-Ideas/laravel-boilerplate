<?php

namespace App\Exceptions;

class NotVerifiedEmailException extends BaseException {
    const STATUS_CODE = 401;
    const MESSAGE = 'Email is not verified';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::STATUS_CODE);
    }
}
