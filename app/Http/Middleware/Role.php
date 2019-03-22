<?php

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Closure;
use Auth;
use App\User;
use Entrust;
use App;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $role) {
        if ($this->auth->guest() || !$request->user()->hasRole($role)) {
            if($request->user()->hasRole('admin'))
                return redirect()->to('dashboard');
            else
                return redirect()->to('user/profile');
        }
        return $next($request);
    }
}
