<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserCheckEmailRequest;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * @SWG\Post(
     *   tags={"Users"},
     *   path="/users/create",
     *   summary="Register new user",
     *   operationId="create",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="ex. John Doe",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="ex. user@test.com",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="[someUniqueValue]",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *         type="string",
     *         name="clientId",
     *         in="header",
     *         required=true
     *   ),
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    function create(UserCreateRequest $request)
    {
        $data = $request->only('email','name','password');

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        //prepare internal request
        $proxy = Request::create(
            '/oauth/token',
            'POST',
            [
                'grant_type'    => 'password',
                'client_id'     => $request->header('clientId'),
                'client_secret' => $request->get('clientSecret'),
                'username'      => $data['email'],
                'password'      => $data['password'],
                'scope'         => null,
            ]
        );

        app()->instance('request', $proxy);

        return \Route::dispatch($proxy);
    }

    /**
     * @SWG\Get(
     *   tags={"Users"},
     *   path="/users/show",
     *   summary="Get logged user",
     *   operationId="me",
     *   produces={"application/json"},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}}
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function show(Request $request)
    {
        return $request->user();
    }
}
