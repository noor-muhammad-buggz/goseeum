<?php

namespace App\Http\Middleware;

use Closure;

class ValidateGetRequest
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
        $except = [];
        // check for post request validity
        if(!$request->isMethod('get')) {
            $response = \Config::get('app.api_response');
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'only get method allowed';
            return \response()->json($response);
        }
        
        // if(!in_array($request->route()->uri, $except)) {
        //     if(!$request->has('token')) {
        //         $response = \Config::get('app.api_response');
        //         $response['status'] = \Config::get('app.failure_status');
        //         $response['message'] = 'token is missing. please provide a token';
        //         return \response()->json($response);
        //     }
        //     else {
        //         $is_exist = User::where(['token' => $request->input('token')])->first();
        //         if(empty($is_exist)) {
        //             $response = \Config::get('app.api_response');
        //             $response['status'] = \Config::get('app.failure_status');
        //             $response['message'] = 'invalid token. please provide a valid token';
        //             return \response()->json($response);
        //         }
        //     }
        // }
        return $next($request);
    }
}
