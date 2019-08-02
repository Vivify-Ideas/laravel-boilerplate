<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception {
    protected $context;

    public function __construct(string $message, int $code, ...$context)
    {
        parent::__construct($message, $code);
        $this->context = $context;
    }

    final public function report()
    {
        \Log::debug($this->message, $this->context);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    final public function render()
    {
        $responseBody = [
            'error' => $this->message
        ];

        if (config('app.debug')) {
            $responseBody['context'] = $this->context;
        }

        return response()->json($responseBody, $this->code);
    }
}
