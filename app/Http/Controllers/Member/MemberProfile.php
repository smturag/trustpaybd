<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MemberProfile extends Controller
{
    public function dashboard()
    {
        return view('member.dashboard');
    }

    public function profile_view(){

        $user =  Auth::user();
        return view('member.member_profile',compact('user'));
    }
    public function member_profile_update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|max:55',
            'email' => 'required',
            'mobile' => 'required|numeric',
        ]);

        $user = User::find($id);
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        if($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect('/profile_view')->with('message', 'Data Update Success');
    }
}
