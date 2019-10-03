<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\NotVerifiedEmailException;

class UserEmailVerifiedMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isVerifyByEmailOn = config('auth.verify_by_email', false);
        $accountNotVerified = !empty(auth()->user()->verify_token);
        if ($isVerifyByEmailOn && $accountNotVerified) {
            throw new NotVerifiedEmailException;
        }
        return $next($request);
    }
}
