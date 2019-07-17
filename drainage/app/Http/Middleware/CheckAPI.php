<?php

namespace App\Http\Middleware;

use Closure;

class CheckAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        return $next($request);

        $isApiUseful = false;

        if($isApiUseful){
//            return $next($request);
        }else{
            $response = [];
            $response['code'] = 1000 ;
            $response['message'] = 'invalid request';
            $response['data'] = [];
            return response()->json($response, 404);
        }
    }
}
