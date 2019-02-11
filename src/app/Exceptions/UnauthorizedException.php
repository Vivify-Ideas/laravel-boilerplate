<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    const STATUS_CODE = 401;
    const MESSAGE = 'Unauthorized';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return response()->json(['error' => self::MESSAGE], self::STATUS_CODE);
    }
}
