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

    public function updateProfile(UpdateProfileRequest $request)
    {
        return $this->_userService->updateProfile(
            auth()->user(),
            Arr::only($request->validated(), [ 'first_name', 'last_name' ]),
            $request->file('avatar')
        );
    }
}
