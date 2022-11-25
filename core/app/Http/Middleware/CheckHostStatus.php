<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckHostStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if (Auth::guard('host')->check()) {
            $user = Auth::guard('host')->user();
            if ($user->status  && $user->ev  && $user->sv) {
                return $next($request);
            } else {
                return redirect()->route('host.authorization');
            }
        }
        abort(403);
    }
}
