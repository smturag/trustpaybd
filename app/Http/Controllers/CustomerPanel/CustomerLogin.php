<?php

namespace App\Http\Controllers\CustomerPanel;

use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use App\Models\Customer;
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




class CustomerLogin extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/customer';

    //
    public function loginfrm()
    {
        $memberDetails = auth('customer')->user();

        if ($memberDetails) {
            return redirect('/customer/dashboard');
        } else {
            return view('customer-panel.customer_login');
        }

        //return view('customer-panel.customer_login');
    }

    public function loginAction(Request $request)
    {
        $browser = getBrowser();

        $myclip = myclientIP();
        $expiresAt = Carbon::now()->addMinutes(10);
        $attmp = Cache::get('customerattmp');
        if (!strlen($attmp)) $attmp = 10;
        //$adminidfound = Customer::where('email',$request->username)->count();
        $session_id = session()->getId();

        $tokenid = getCookie();

        if (!empty($tokenid)) {
            $tokenid = getCookie();
        } else {
            $tokenid = setLoginCookie();
        }

        if (Auth::guard('customer')->attempt(['email' => $request->username, 'password' => $request->password])) {
            Cache::forget('customerattmp');
            $secretcode = sha1(uniqid(rand(), true));
            $admin_user = Customer::findOrFail(Auth::guard('customer')->user()->id);

            $admin_last_login_count = $admin_user->last_login_count;

            if ($admin_user->status != 1) {
                $this->guard('customer')->logout();
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
            return redirect()->route('customer_dashboard');
        }

        $attmp = $attmp - 1;
        $faildattmp = $attmp;

        if ($attmp < 1) {
            session()->flash('alert', 'Your Login ID is block Please Contact with Administration');
            Session::flash('type', 'warning');
            return redirect()->back()->withInput();
        }

        Cache::put('customerattmp', $attmp, $expiresAt);

        session()->flash('alert', 'Dear User Wrong password. Try again or click Forgot password to reset it ' . $faildattmp . ' attempt left');
        Session::flash('type', 'warning');
        return redirect()->back()->withInput();
        //return 'user and password not valid';
        //return $request->all();
    }

    public function profile()
    {
        $profile_data = Auth('customer')->user();
        return view('customer-panel.enhanced_profile', compact('profile_data'));
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth('customer')->user();
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
        $user = Customer::find(Auth('customer')->user()->id);

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
        // Update other fields as needed
        $user->save();
        return redirect()->back()->with('success', 'Password changed successfully.');

    }

    public function view_create_customer(){
        return view('customer-panel.customer_sign_up');
    }

    public function customer_sign_up(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20||unique:customers',
            'email' => 'required|email|unique:customers,email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new Customer();
        $user->customer_name = $request->input('name');
        $user->mobile = $request->input('mobile');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $verificationToken = Str::random(40);
        $user->verification_token = $verificationToken;
        $user->verification_token_expires_at = Carbon::now()->addMinutes(1);
        $user->save();

$verificationLink = route('customer.verify', ['token' => $verificationToken]);

// Send the verification email
Mail::to($user->email)->send(new VerificationEmail($verificationLink));

        return view('primary.make_verification',compact('user'));

    }

    public function verify_customer($token)
{
    $user = Customer::where('verification_token', $token)
                   ->where('verification_token_expires_at', '>', now())
                   ->first();

    if ($user) {

        $user->email_verified_at = now();
        $user->save();
        return Redirect::route('customerlogin')->with('success', 'Email verified successfully. You can now log in.');
    } else {

        return Redirect::route('customerlogin')->with('message', 'Invalid or expired verification link.');
    }
}

public function verify_new_token(Request $request){

        $user = Customer::find($request->id);

        if($user->email_verified_at ==null){
            $verificationToken = Str::random(40);
            $user->verification_token = $verificationToken;
            $user->verification_token_expires_at = Carbon::now()->addMinutes(1); // The link expires in 1 minute
            $user->save();
            $verificationLink = route('customer.verify', ['token' => $verificationToken]);

            Mail::to($user->email)->send(new VerificationEmail($verificationLink));

            return view('primary.make_verification',compact('user'));
        }
        return Redirect::route('customerlogin');
}

public function forget_password(){
    return view('primary.forget_pass_view');
}

public function forget_password_customer(Request $request){

    $data= Customer::where('email',$request->email)->first();
    if($data){

        $new_password = Str::random(10);
        Mail::to($data->email)->send(new ResetPassword($new_password,$data->customer_name));
        $data->password = Hash::make($new_password);
        $data->save();
        return Redirect::route('customerlogin')->with('message', ' Password changed successfully. Password send to your email. Please check your email');;

    }
   return redirect()->back()->with('alert', 'No Email found');



}

}
