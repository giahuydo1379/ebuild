<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Auth as HAuth;

class CheckPermissions
{
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
        $user = HAuth::getUserInfo();

        if (HAuth::is_admin($user)) {
            return $next($request);
        }

        $action = \App\Helpers\General::get_controller_action();
//dd($action);
        if (HAuth::has_permission($action['as'], $user)) {
            return $next($request);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'Forbidden'],403);
        }

//        return response('You need to login again.', 403);
        return response()->view('errors.403', []);
    }
}
