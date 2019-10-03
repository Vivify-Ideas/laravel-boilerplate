<?php

namespace App\Http\Controllers\User;

use App\Services\User\UserService;
use App\Http\Requests\User\VerifyEmailRequest;

class VerifyEmailController {
    /**
     * @var UserService
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
     * @SWG\Get(
     *   tags={"User"},
     *   path="/user/verify",
     *   summary="Verify user email",
     *   operationId="userVerifyEmail",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="verify_token",
     *     in="query",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param VerifyEmailRequest $request
     */
    public function store(VerifyEmailRequest $request)
    {
        $this->_userService->verifyEmail($request->get('verify_token'));

        return view('user.email_verified');
    }
}
