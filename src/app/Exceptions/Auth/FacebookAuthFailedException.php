<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BaseException;

class FacebookAuthFailedException extends BaseException {
    const STATUS_CODE = 401;
    const MESSAGE = 'Facebook API returned an error';

    public function __construct(...$context)
    {
        parent::__construct(self::MESSAGE, self::STATUS_CODE, $context);
    }
}
