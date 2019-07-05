<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Services\User\UserService;
use App\Http\Requests\User\UpdateProfileRequest;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Requests\User\SearchUserRequest;

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

    /**
     * Update users profile
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
     * @SWG\Get(
     *   tags={"User"},
     *   path="/user/search",
     *   summary="Search users that are registered on application",
     *   operationId="usersSearch",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="term",
     *     in="query",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="size",
     *     in="query",
     *     required=false,
     *     type="integer"
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param SearchUserRequest $request
     */
    public function search(SearchUserRequest $request)
    {
        return $this->_userService->search(
            $request->get('term'),
            $request->get('size', 5)
        );
    }
}
