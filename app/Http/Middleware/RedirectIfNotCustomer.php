<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotCustomer
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = 'customer')
	{
	    if (!Auth::guard($guard)->check()) {
	        return redirect('mc/login');
	    }

		$verify = Auth::guard($guard)->user()->email_verified_at;		

		if ($verify==null) {
			Auth::guard($guard)->logout();
			return redirect()
			->route('mclogin')
			->with('message', 'You need to confirm your account. We have sent you an activation code, please check your email.');
		}

	    return $next($request);
	}
}