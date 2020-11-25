<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Services\User\UserService;
use App\Http\Requests\User\UpdateProfileRequest;
use Illuminate\Support\Arr;

class UserController extends Controller {

    /**
     * @var UserService $_userService
     */
    private $_userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->_userService = $userService;
    }

    /**
     * @OA\Post(
     *   tags={"User"},
     *   path="/user/change-password",
     *   summary="Change user password",
     *   operationId="usersChangePassword",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *     @OA\JsonContent(
     *       required={"current_password", "new_password", "new_password_confirmation"},
     *       @OA\Property(property="current_password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="new_password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="new_password_confirmation", type="string", format="password", example="Pass12345")
     *     ),
     *   ),
     *   security={{"authorization_token":{}}},
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *   @OA\Response(response=422, description="Validation failed", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent())
     * )
     * Change active user password to the new one
     *
     * @param UserChangePasswordRequest $request
     * @return void
     */
    public function changePassword(UserChangePasswordRequest $request)
    {
        $password = $request->get('new_password');

        return $this->_userService->changePassword(
            auth()->user(),
            $password
        );
    }

    /**
     * @OA\Post(
     *   tags={"User"},
     *   path="/user",
     *   summary="Change user first name, last name and avatar",
     *   operationId="userUpdateProfile",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *     @OA\JsonContent(
     *       required={},
     *       @OA\Property(property="first_name", type="string", example="ex. John"),
     *       @OA\Property(property="last_name", type="string", example="ex. Doe"),
     *       @OA\Property(property="avatar", type="file")
     *     ),
     *   ),
     *   security={{"authorization_token":{}}},
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *   @OA\Response(response=422, description="Validation failed", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent())
     * )
     *
     * Update user profile information and avatar
     *
     * @param UpdateProfileRequest $request
     * @return void
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        return $this->_userService->updateProfile(
            auth()->user(),
            Arr::only($request->validated(), [ 'first_name', 'last_name' ]),
            $request->file('avatar')
        );
    }

    /**
     * @OA\Post(
     *   tags={"User"},
     *   path="/user/verify/resend",
     *   summary="Resend user verification email",
     *   operationId="userResendVerificationEmail",
     *   security={{"authorization_token":{}}},
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *   @OA\Response(response=422, description="Validation failed", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent())
     * )
     *
     * Send verification email to the user
     *
     * @return void
     */
    public function sendVerifyEmail()
    {
        return $this->_userService->generateAndSendVerifyEmail(
            auth()->user()
        );
    }
}
