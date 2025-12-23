<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Cache;
use App\Models\User;
use App\Models\Agent;
use App\Models\Admin;
use Session;
use Carbon\Carbon;
class LastUserActivity
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
		
		if (Auth::guard('admin')->check()) {

			$adminauthid = auth('admin')->user()->id;


			$admin_safepass = auth('admin')->user()->safepass;
			$admin_pass_expire = auth('admin')->user()->pass_expire;
			$adminpexpire=datetoDiff($admin_pass_expire);

            if ($adminpexpire<=1){
				Admin::where('id', $adminauthid)->update([
				'safepass' => 0
				]);
			}
			if($admin_safepass==0){

				return redirect()->route('admin_passPinChange');
			}


        }
		
		  elseif (Auth::guard('agent')->check()) {

			$authid = auth('agent')->user()->id;

			$safepass = auth('agent')->user()->safepass;
			$pass_expire = auth('agent')->user()->pass_expire;
			$pexpire=datetoDiff($pass_expire);

            if ($pexpire<=1){
				Agent::where('id', $authid)->update([
				'safepass' => 0
				]);
			}
			if($safepass==0){

				return redirect()->route('login_pass_change', 'agent');
			}


        }
		
		
        elseif (Auth::guard('web')->check()) {

			$authid = auth('web')->user()->id;

			$safepass = auth('web')->user()->safepass;
			$pass_expire = auth('web')->user()->pass_expire;
			$pexpire=datetoDiff($pass_expire);

            if ($pexpire<=1){
				User::where('id', $authid)->update([
				'safepass' => 0
				]);
			}
			if($safepass==0){

				return redirect()->route('login_pass_change', 'user');
			}


        }
		
		 

		

        return $next($request);
    }
}
