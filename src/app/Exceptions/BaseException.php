<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception {
    private $_context;

    /**
     * Undocumented function
     *
     * @param integer $responseCode
     * @param string $exceptionMessage
     * @param array ...$context
     */
    public function __construct(int $responseCode, string $exceptionMessage, ...$context)
    {
        parent::__construct($exceptionMessage, $responseCode);
        $this->_context = $context;
    }

    /**
     * Log error the to laravel.log file
     *
     * @return void
     */
    final public function report()
    {
        \Log::debug($this->message, $this->_context);
    }

    /**
     * Returns the error in json format, and if app is in debug mode
     * then we render complete error
     *
     * @return void
     */
    final public function render()
    {
        $responseBody = ['error' => $this->message];
        if (config('app.debug')) {
            $responseBody['context'] = $this->_context;
        }
        return response()->json($responseBody, $this->code);
    }
}
