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
     * @SWG\Post(
     *   tags={"User"},
     *   path="/user/forgot-password",
     *   summary="Send a forgot password email with link",
     *   operationId="userForgotPassword",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * Send forgot password link in email
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
     * @SWG\Post(
     *   tags={"User"},
     *   path="/user/reset-password",
     *   summary="Reset password ",
     *   operationId="userResetPassword",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="token",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password_confirmation",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
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
