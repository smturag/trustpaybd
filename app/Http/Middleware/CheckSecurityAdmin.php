<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminAccessLog;
use App\Models\DeviceLog;
use Session;
use App\Models\Admin;

class CheckSecurityAdmin
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
            $userauthid = auth('admin')->user()->id;
            $admingetdata = Admin::find($userauthid);
            $userlog = AdminAccessLog::where('admin_id', auth('admin')->user()->id)
                ->orderBy('id', 'desc')
                ->first();
            $tokenid = getCookie();

            $accesscode = Session::get('accesscode');

            if ($accesscode == $userlog->accesscode and auth('admin')->user()->status == 1) {
                $devicelog = DeviceLog::where('user_id', auth('admin')->user()->id)
                    ->where('login_user_type', 'admin')
                    ->where('device_id', $tokenid)
                    ->where('access_id', $accesscode)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($devicelog->remember == 1 or $userlog->pincode == 1) {
                    if (auth('admin')->user()->otp == 'no') {
                        return $next($request);
                    } else {
                        if ($admingetdata->otp == 'yes' && ($admingetdata->otp_code = $userlog->otpcode)) {
                            return $next($request);
                        } else {
                            return redirect()->route('two_step', 'admin');
                        }
                    }
                } else {
                    return redirect()->route('admin_login_pin');
                }
            } else {
                // return "not ";
                return redirect()->route('adminlogout');
            }
        }
    }
}
