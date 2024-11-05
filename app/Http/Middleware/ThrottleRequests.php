<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests as Middleware;

class ThrottleRequests extends Middleware
{
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        return parent::handle($request, $next, $maxAttempts, $decayMinutes);
    }
}
