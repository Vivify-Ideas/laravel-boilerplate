<?php

namespace App\Services\Oauth;

use Illuminate\Http\Request;
use Laravel\Passport\Client;

/**
 * Class Security manipulates security issues related to OAUTH
 * @package App\Services\Oauth
 */
class SecurityService
{
    /**
     * Check headers for client authorization credentials (clientId and clientSecret)
     *
     * @param Request $request
     *
     * @return Client|null
     */
    public function getOauthClient(Request $request)
    {
        //validate Client credentials
        $client = Client::where('password_client', 1)
            ->where('id', '=', $request->header('clientId'))
            ->first();

        return $client;
    }
}