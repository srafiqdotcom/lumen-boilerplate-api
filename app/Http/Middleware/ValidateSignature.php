<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ValidateSignature
{
    public function handle($request, Closure $next)
    {
        if (! URL::hasValidSignature($request)) {
            throw new AccessDeniedHttpException('Invalid or Expired URL Signature.');
        }

        return $next($request);
    }
}
