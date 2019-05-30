<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Services\User\ForgotPasswordService;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\ResetPasswordRequest;

class ForgotPasswordController extends Controller {

    /**
     * @var ForgotPasswordService $_forgotPasswordService
     */
    private $_forgotPasswordService;

    /**
     * @param UserService $userService
     */
    public function __construct(ForgotPasswordService $forgotPasswordService)
    {
        $this->_forgotPasswordService = $forgotPasswordService;
    }

    /**
     * Send an email to the user with token for reset password
     *
     * @param ForgotPasswordRequest $request
     * @return void
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->_forgotPasswordService->sendForgotPasswordToken(
            $request->get('email')
        );

        return response()->json([
            'message' => 'Email sent'
        ]);
    }

    /**
     * Change user password
     *
     * @param ResetPasswordRequest $request
     * @return void
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->_forgotPasswordService->resetPassword(
            $request->get('token'),
            $request->get('password')
        );
    }
}
