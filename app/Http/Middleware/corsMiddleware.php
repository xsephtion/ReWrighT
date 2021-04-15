<?php

namespace App\Http\Middleware;

use Closure;

class corsMiddleware
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
    * @param $request
    * @param Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next) {
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin' , '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application','ip');
        return $response;
    }
}
