<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Services\User\UserService;

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
}
