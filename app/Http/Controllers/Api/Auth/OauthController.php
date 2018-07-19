<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRefreshTokenRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OauthController extends Controller
{
    /**
     * @SWG\Post(
     *   tags={"Oauth"},
     *   path="/auth/login",
     *   summary="Login user",
     *   operationId="login",
     *   produces={"application/json"},
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
     *     type="string",
     *     name="clientId",
     *     in="header",
     *     required=true
     *   ),
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    function login(UserLoginRequest $request)
    {
        $data = $request->only('email', 'password');
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
     * @SWG\Post(
     *   tags={"Oauth"},
     *   path="/auth/refresh-token",
     *   summary="Refresh oauth token",
     *   operationId="refresh",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="refresh_token",
     *     in="formData",
     *     description="ex. [the_refresh_token]",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     type="string",
     *     name="clientId",
     *     in="header",
     *     required=true
     *   ),
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param UserRefreshTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    function refresh(UserRefreshTokenRequest $request)
    {
        $data = $request->only('refresh_token');
        $proxy = Request::create(
            '/oauth/token',
            'POST',
            [
                'grant_type'    => 'refresh_token',
                'client_id'     => $request->header('clientId'),
                'client_secret' => $request->get('clientSecret'),
                'refresh_token' => $data['refresh_token'],
                'scope'         => null,
            ]
        );

        app()->instance('request', $proxy);

        return \Route::dispatch($proxy);

    }
}
