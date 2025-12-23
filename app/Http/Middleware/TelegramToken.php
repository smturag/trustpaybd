<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TelegramToken
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $authorization = $request->header('X-Admin-Authorization');
        $key = "?9b/dr0%E&r!sXPmoifuJWiRzS#T?4U4DxytyfcvXmTZN[eGK+-tP;}jwaI)^)}";

	   // if ($authorization != $key) {
	      //  return response()->json(['message' => 'Unauthorized: Invalid API credentials.'], 401);
	  //  }

	    return $next($request);
	}
}