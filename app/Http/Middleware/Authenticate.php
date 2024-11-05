<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\DB;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Enable query logging if LOG_QUERIES is enabled in the environment.
        if (env('LOG_QUERIES')) {
            DB::enableQueryLog();

            // Process the request and get the response.
            $response = $next($request);

            // Get logged queries and append to response if JSON.
            $queries = DB::getQueryLog();
            if ($response->headers->get('content-type') === 'application/json') {
                $data = json_decode($response->getContent(), true);
                $data['queries'] = $queries;
                $response->setContent(json_encode($data));
            }

            return $response;
        }

        return $next($request);
    }
}
