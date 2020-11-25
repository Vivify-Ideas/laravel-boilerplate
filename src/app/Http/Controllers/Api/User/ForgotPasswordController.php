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
     * @OA\Post(
     *   tags={"User"},
     *   path="/user/forgot-password",
     *   summary="Send a forgot password email with link",
     *   operationId="userForgotPassword",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *     @OA\JsonContent(
     *       required={"email"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com")
     *     ),
     *   ),
     *   security={{"authorization_token":{}}},
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *   @OA\Response(response=422, description="Validation failed", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent())
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
     * @OA\Post(
     *   tags={"User"},
     *   path="/user/reset-password",
     *   summary="Reset password ",
     *   operationId="userResetPassword",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *     @OA\JsonContent(
     *       required={"token", "password", "password_confirmation"},
     *       @OA\Property(property="token", type="string"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="PassWord12345")
     *     ),
     *   ),
     *   security={{"authorization_token":{}}},
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *   @OA\Response(response=422, description="Validation failed", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent())
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
