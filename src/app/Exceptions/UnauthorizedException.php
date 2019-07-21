<?php

namespace App\Exceptions;

class UnauthorizedException extends BaseException {
    const STATUS_CODE = 401;
    const MESSAGE = 'Unauthorized';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::STATUS_CODE);
    }
}
