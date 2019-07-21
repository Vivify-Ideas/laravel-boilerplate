<?php

namespace App\Exceptions;

class TokenExpiredException extends BaseException {
    const STATUS_CODE = 401;
    const MESSAGE = 'Token has expired';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::STATUS_CODE);
    }
}
