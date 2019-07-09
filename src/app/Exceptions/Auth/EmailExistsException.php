<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BaseException;

class EmailExistsException extends BaseException {
    const STATUS_CODE = 422;
    const MESSAGE = 'This email already exists';

    public function __construct(...$context)
    {
        parent::__construct(self::STATUS_CODE, self::MESSAGE, ...$context);
    }
}
