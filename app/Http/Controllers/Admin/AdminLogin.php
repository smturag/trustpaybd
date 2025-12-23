<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;

use Carbon\Carbon;
use Session;
use Cache;

use App\Lib\GoogleAuthenticator;
use App\Models\Admin;
use App\Models\AdminAccessLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Modem;

use App\Models\PaymentRequest;


class AdminLogin extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    public function loginfrm()
    {
        $mamberDetails = auth('admin')->user();

        //return $mamberDetails;

        if ($mamberDetails) {
            return redirect('/admin/dashboard');
        } else {
            return view('admin.admin_login');
        }

        return view('admin.admin_login');
    }

    public function loginAction(Request $request)
    {
        $browser = getBrowser();
        $myclip = myclientIP();
        $expiresAt = Carbon::now()->addMinutes(10);
        $attmp = Cache::get('adminattmp');
        if (!strlen($attmp)) {
            $attmp = 10;
        }
        $adminidfound = Admin::where('username', $request->username)->count();
        $session_id = session()->getId();

        $tokenid = getCookie();

        if (!empty($tokenid)) {
            $tokenid = getCookie();
        } else {
            $tokenid = setLoginCookie();
        }

        if (
            Auth::guard('admin')->attempt([
                'username' => $request->username,
                'password' => $request->password,
            ])
        ) {
            $attmpforget = Cache::forget('adminattmp');
            $secretcode = sha1(uniqid(rand(), true));

            $admin_user = Admin::findOrFail(Auth::guard('admin')->user()->id);

            $admin_last_login_count = $admin_user->last_login_count;
            $admin_type = $admin_user->type;

            if ($admin_user->status != 1) {
                $this->guard('admin')->logout();
                $request->session()->invalidate();
                session()->flash('alert', 'Your Login ID is block Please Contact with Administration');
                Session::flash('type', 'warning');
                return redirect()->back();
            }

            $admin_user->update([
                'access_code' => $secretcode,
                'last_login' => Carbon::now()->toDateTimeString(),
                'last_login_count' => $admin_last_login_count + 1,
                'last_ip' => $myclip,
            ]);
            $request->session()->put('accesscode', $secretcode);

            $current_longitude = $request->current_longitude;
            $current_latitude = $request->current_latitude;

            // access log
            AdminAccessLog::create([
                'admin_id' => $admin_user->id,
                'admin_type' => $admin_type,
                'ip_address' => $myclip,
                'tokenid' => $tokenid,
                'session_id' => $session_id,
                'browser' => $browser['name'],
                'login_with' => 'web',
                'platform' => $browser['platform'],
                'longitude' => $current_longitude,
                'latitude' => $current_latitude,
                'pincode' => $pinsts,
                'accesscode' => $secretcode,
            ]);
            return redirect()->route('admin_dashboard');
        }

        $attmp = $attmp - 1;
        $faildattmp = $attmp;
        if ($attmp < 1) {
            session()->flash('alert', 'Your Login ID is block Please Contact with Administration');
            Session::flash('type', 'warning');
            return redirect()->back()->withInput();
        }

        Cache::put('adminattmp', $attmp, $expiresAt);

        session()->flash('alert', 'Dear User Wrong password. Try again or click Forgot password to reset it ' . $faildattmp . ' attempt left');
        Session::flash('type', 'warning');
        return redirect()->back()->withInput();
        return 'user and password not valid';

        return $request->all();
    }

    public function profile()
    {
        $data = Admin::findOrFail(Auth::guard('admin')->user()->id);

        return view('admin.profile.profile', compact('data'));
    }

    public function update_profile(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'username' => 'required',
            'mobile' => 'required|max:13|min:11',
            'email' => 'required',
            'pincode'=> 'required',
        ];

        $validatedData = $request->validate($rules);

        $user = Admin::findOrFail(Auth::guard('admin')->user()->id);

        if ($request->hasFile('profile_pic')) {
            // Validate the uploaded file
            $request->validate([
                'profile_pic' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete the previous profile picture if it exists
            if ($user->profile_pic) {
                Storage::disk('public')->delete($user->profile_pic);
            }

            // Store the uploaded file
            $file = $request->file('profile_pic');

            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('Profile', $fileName, 'public');
            $user->profile_pic = $path;

            $user->save();
        }
        $user->admin_name = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile = $request->input('mobile');
        $user->username = $request->input('username');
        $user->pincode = $request->input('pincode');
        // Update other fields as needed
        $user->save();
        return redirect()->back()->with('success', 'Update profile successfully.');
    }

    public function update_password(Request $request)
    {
        $user = Admin::findOrFail(Auth::guard('admin')->user()->id);

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()
                ->back()
                ->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Regenerate the session to avoid session fixation
        $request->session()->invalidate();

        // Generate a new session token
        $request->session()->regenerateToken();

        // Redirect the user to the login page (or another route)
        return redirect()->route('adminlogin')->with('success', 'You have been logged out successfully.');
    }

public function checkingMember()
{
    $processed = 0;
    $updated = 0;
    $failed  = 0;

    // Process in chunks of 200 (tune as needed)
    PaymentRequest::where('sim_id', 'not sim id')
        ->chunk(5, function ($requests) use (&$processed, &$updated, &$failed) {
            foreach ($requests as $request) {
                $processed++;

                try {
                    $checkTransactionResponse = checkTransaction($request->payment_method_trx);

                    if ($checkTransactionResponse['status'] === 'success') {
                        if (
                            (int)$checkTransactionResponse['data']['amount'] === (int)$request->amount &&
                            strtolower($checkTransactionResponse['data']['method']) === strtolower($request->payment_method)
                        ) {
                            if (
                                isset($checkTransactionResponse['data']['receiverPhone']) &&
                                $checkTransactionResponse['data']['receiverPhone'] !== 'UNKNOWN'
                            ) {
                                $agent = Modem::where('sim_number', $checkTransactionResponse['data']['receiverPhone'])->first();

                                if ($agent) {
                                    $request->agent   = $agent->member_code;
                                    $request->partner = getPartnerFromAgent($agent->member_code)->member_code ?? null;
                                    $request->modem_id = $agent->id;
                                    $request->sim_id   = $checkTransactionResponse['data']['receiverPhone'];
                                    $request->save();

                                    $updated++;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $failed++;
                    \Log::error("PaymentRequest ID {$request->id} failed: " . $e->getMessage());
                }
            }
        });

    return [
        'processed' => $processed,
        'updated'   => $updated,
        'failed'    => $failed,
    ];
}


}
