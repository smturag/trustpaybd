<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Models\User;
use App\Models\UserAccessLog;

class MemberSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            $userauthid = Auth::guard('web')->user()->id;
            $usergetdata = User::find($userauthid);
            $accesscode = Session::get('accesscode');
            $tokenid = getCookie();

            $userlog = UserAccessLog::where('user_type', 'member')->where('user_id', $userauthid)->orderBy('id', 'desc')->first();

            if ($accesscode == $userlog->accesscode and auth('web')->user()->status == 1) {
                if (auth('web')->user()->otp == 'no') {
                    return $next($request);
                } else {
                    if ($usergetdata->otp == 'yes' && ($usergetdata->otp_code = $userlog->otpcode)) {
                        return $next($request);
                    } else {
                        return redirect()->route('member_otp');
                    }
                }
            } else {
                return redirect()->route('userlogout');
            }
        }
        //return $next($request);
    }
}
