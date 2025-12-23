<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Carbon\Carbon;
use Mail;
use Cache;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Lib\GoogleAuthenticator;

use App\Models\User;
use App\Models\UserAccessLog;
use App\Models\Ipblock;
use App\Models\FailedLog;

class MemberLogin extends Controller
{
    //

    use AuthenticatesUsers;

    public function login(){

	    // $mamberDetails = auth('web')->user();
		//  if($mamberDetails) {
		//   return redirect('/home');
		//  }

	//    return view('member/memberlogin');
       return view('auth/main_login');

   }

   public function LoginAction(Request $request){

        $browser = getBrowser();
        $myclip = myclientIP();
        $device_remember = 0;
        $expiresAt = Carbon::now()->addMinutes(60);

        $this->validate($request,[
            'username' =>'required|min:5',
            'password' => 'required|min:5',
            //'g-recaptcha-response' => 'required|captcha',
        ]);

        $attmp = Cache::get('attmp');

	    if (!strlen($attmp)) $attmp=7;

        $tokenid = getCookie();
        if(!empty($tokenid)) {
            $tokenid = getCookie();
        }else {
            $tokenid = setLoginCookie();
        }

        $session_id = session()->getId();


        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        $useridfound = User::where('db_status', 'live')->where($fieldType,$request->username)->count();
			if($useridfound==0){

				session()->flash('alert', 'Dear User not found. Try again or click Forgot password to reset it. '.$attmp.' attempt left');
					Session::flash('type', 'warning');
					return redirect()->back()->withInput();

			}



            if (Auth::guard('web')->attempt([
                $fieldType => $request->username,
                'password' => $request->password
                ]))
                {

                    $attmpforget = Cache::forget('attmp');

                    $secretcode=sha1(uniqid(rand(),true));

                    $user = User::findOrFail(Auth::guard('web')->user()->id);

                    $last_login_count = $user->last_login_count;

                    if($user->status == 1) {
                        $user->update([
                           'access_code' => $secretcode,
                           'last_login' => Carbon::now()->toDateTimeString(),
                           'last_login_count' => $last_login_count+1,
                           'last_ip' => $myclip,
                           'device_id' => $tokenid
                       ]);

                       $request->session()->put('accesscode', $secretcode);

                       $uacces = UserAccessLog::create([
                           'user_id' => $user->id,
                           'user_type' => 'member',
                           'ip_address' => $myclip,
                           'tokenid' => $tokenid,
                           'session_id' => $session_id,
                           'browser' => $browser['name'],
                           'login_with' => 'web',
                           'platform' => $browser['platform'],
                           'pincode' => $pinsts,
                           'accesscode' => $secretcode,
                       ]);
                       return redirect()->route('memberdashboard');

                       }else {

                       $this->guard('web')->logout();
                       $request->session()->invalidate();
                       session()->flash('alert', 'Your Login ID is block Please Contact with Administration');
                       Session::flash('type', 'warning');
                       return redirect()->back();

                    }

                }else {

                    $attmp=$attmp-1;
					$faildattmp=$attmp;

					if ($attmp<1){

                        Ipblock::create([
							'ip_address'=>$myclip,
							'sesion_id'=>$session_id,
							'browser'=>$browser['name'],
							'message'=>'ip block for reseller',
							'error_count'=>$attmp
							]);

                    }

                    Cache::put('attmp', $attmp, $expiresAt);

					if($faildattmp==1){
						$faildattmp= 'lock';
					}

					$message = 'Visitor Attempt ' .$faildattmp.' Username '.$request->username. ' and password '.$request->password;
					if($useridfound>0) {
						$userid = User::where($fieldType,$request->username)->first()->id;
					}else {
						$userid = 0;
					}

					FailedLog::create([
						'user_id'=>$userid,
						'session_id'=>$session_id,
						'device_id'=>$tokenid,
						'logs'=>$message,
						'ip_address'=>$myclip,
						'login_with'=>$browser['name']. ' '. $browser['platform'],
						'login_with'=>'web',
					]);

					session()->flash('alert', 'Dear User Wrong password. Try again or click Forgot password to reset it '.$faildattmp.' attempt left');
					Session::flash('type', 'warning');
					return redirect()->back()->withInput();
                }



   }


   public function userlogout(Request $request){
        $mamberDetails = auth('web')->user();
        if($mamberDetails) {

            $usercount = UserAccessLog::where('user_id',auth('web')->user()->id)->orderBy('id', 'desc')->count();
            if($usercount>0) {
            $user = UserAccessLog::where('user_id',auth('web')->user()->id)->orderBy('id', 'desc')->first();
            $user->logout_time = Carbon::now()->toDateTimeString();
            $user->save();
            }
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/login');

        //return redirect('/admin');
        }else {
            return redirect('/agent');
        }

        ///$user = User::findOrFail(auth('web')->user()->id);

    }

}
