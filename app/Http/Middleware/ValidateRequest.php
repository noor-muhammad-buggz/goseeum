<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class ValidateRequest
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
        $except = ['api/user/register', 'api/user/login'];
        // check for post request validity
        if(!$request->isMethod('post')) {
            $response = \Config::get('app.api_response');
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'only post method allowed';
            return \response()->json($response);
        }
        
        if(!in_array($request->route()->uri, $except)) {
            if(!$request->has('token')) {
                $response = \Config::get('app.api_response');
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'token is missing. please provide a token';
                return \response()->json($response);
            }
            else {
                $is_exist = User::where(['token' => $request->input('token')])->first();
                if(empty($is_exist)) {
                    $response = \Config::get('app.api_response');
                    $response['status'] = \Config::get('app.failure_status');
                    $response['message'] = 'invalid token. please provide a valid token';
                    return \response()->json($response);
                }
                $request->merge(['user' => $is_exist]);
            }
        }
        return $next($request);
    }
}
