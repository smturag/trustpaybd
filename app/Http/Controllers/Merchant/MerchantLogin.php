<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use App\Models\Merchant;
use App\Models\Admin;
use Cache;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Mail\VerificationEmail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;


class MerchantLogin extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/merchant';

    //
    public function loginfrm()
    {
        $memberDetails = auth('merchant')->user();

        if ($memberDetails) {
            return redirect('/merchant/dashboard');
        } else {
            return view('merchant.merchant_login');
        }

        return view('merchant.merchant_login');

    }

    public function loginAction(Request $request)
    {
        $browser = getBrowser();

        $myclip = myclientIP();
        $expiresAt = Carbon::now()->addMinutes(10);
        $attmp = Cache::get('merchantattmp');
        if (!strlen($attmp)) $attmp = 10;
        //$adminidfound = Merchant::where('email',$request->username)->count();
        $session_id = session()->getId();

        $tokenid = getCookie();

        if (!empty($tokenid)) {
            $tokenid = getCookie();
        } else {
            $tokenid = setLoginCookie();
        }

        if (Auth::guard('merchant')->attempt([
            'email' => $request->username,
            'password' => $request->password
        ])) {
            Cache::forget('merchantattmp');
            $secretcode = sha1(uniqid(rand(), true));
            $admin_user = Merchant::findOrFail(Auth::guard('merchant')->user()->id);

            $admin_last_login_count = $admin_user->last_login_count;

            if ($admin_user->status != 1) {

                $this->guard('merchant')->logout();
                $request->session()->invalidate();
                session()->flash('alert', 'Your Login ID is block Please Contact with Administration');
                Session::flash('type', 'warning');
                return redirect()->back();
            }

            $admin_user->update([
                'access_code' => $secretcode,
                'last_login' => Carbon::now()->toDateTimeString(),
                'last_login_count' => $admin_last_login_count + 1,
                'last_ip' => $myclip
            ]);
            return redirect()->route('merchant_dashboard');
        }

        $attmp = $attmp - 1;
        $faildattmp = $attmp;
        if ($attmp < 1) {

            session()->flash('alert', 'Your Login ID is block Please Contact with Administration');
            Session::flash('type', 'warning');
            return redirect()->back()->withInput();

        }

        Cache::put('merchantattmp', $attmp, $expiresAt);

        session()->flash('alert', 'Dear User Wrong password. Try again or click Forgot password to reset it ' . $faildattmp . ' attempt left');
        Session::flash('type', 'warning');
        return redirect()->back()->withInput();
        return 'user and password not valid';

        return $request->all();

    }

    public function profile()
    {
        $profile_data = Auth('merchant')->user();
        return view('merchant.enhanced_profile', compact('profile_data'));
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth('merchant')->user();
        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');

        if (!Hash::check($currentPassword, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // // Update the password
        $user->password = Hash::make($newPassword);
        $user->save();
        return redirect()->back()->with('success', 'Password changed successfully.');
    }


    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            "mobile" => 'required|string',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the user profile
        $user = Merchant::find(Auth('merchant')->user()->id);

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


        $user->fullname = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile = $request->input('mobile');
        $user->username = $request->input('username');
        $user->pincode = $request->input('pincode');
        // Update other fields as needed
        $user->save();
        return redirect()->back()->with('success', 'Password changed successfully.');

    }

    public function merchant_forget_password(Request $request){

        $data= Merchant::where('email',$request->email)->first();

        if($data){

            $new_password = Str::random(10);
            Mail::to($data->email)->send(new ResetPassword($new_password,$data->fullname));
            $data->password = Hash::make($new_password);
            $data->save();
            return Redirect::route('merchantlogin')->with('message', ' Password changed successfully. Password send to your email. Please check your email');;

        }
        return redirect()->back()->with('alert', 'No Email found');

    }

    public function logout(Request $request){
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/merchant');
    }

    /**
     * Return to admin panel after impersonation
     */
    public function returnToAdmin()
    {
        $adminId = Session::get('impersonate_admin_id');
        
        if (!$adminId) {
            return redirect()->route('merchantlogout');
        }
        
        // Find admin
        $admin = Admin::find($adminId);
        
        if (!$admin) {
            Session::forget('impersonate_admin_id');
            return redirect()->route('merchantlogout');
        }
        
        // Logout from merchant
        Auth::guard('merchant')->logout();
        
        // Remove impersonation flag
        Session::forget('impersonate_admin_id');
        
        // Login as admin
        Auth::guard('admin')->login($admin);
        
        // Redirect to merchant list
        return redirect()->route('merchantList')->with('message', 'You have returned to admin panel');
    }

}
