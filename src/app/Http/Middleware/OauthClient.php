<?php

namespace App\Http\Middleware;

use App\Services\Oauth\SecurityService;
use Closure;

class OauthClient
{
    private $oauthSecurityService;

    /**
     * LoginController constructor.
     * @param $oauthSecurityService
     */
    public function __construct(SecurityService $oauthSecurityService)
    {
        $this->oauthSecurityService = $oauthSecurityService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //check oauth client rights
        $client = $this
            ->oauthSecurityService
            ->getOauthClient($request);

        // Check for valid client
        if (!$client) {
            abort(401, 'Not authorized.' );
        }

        // injecting client_secret parameter
        $request->attributes->add(['clientSecret' => $client->secret]);

        return $next($request);
    }
}
