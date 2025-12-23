<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Session;
use Auth;
use Mail;

use App\Models\User;

class MemberController extends Controller
{
    public function member_add(Request $request)
    {
		return view('member.agent.agent_add_form');
	}

	public function member_addAction(Request $request)
    {



			DB::beginTransaction();

			$validatedData = $request->validate([
			'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6',
			'mobile' => 'required|unique:users|max:255',
            'fullname' => ['required', 'regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/'],
			]);


			$authid = auth()->user('web')->id;


			$user_type = auth()->user('web')->user_type;

			if($user_type=='agent') {

				return redirect('/dashboard')->with('alert', 'not access');

			}

			$dblavel = [];

			if($user_type=='partner') {

				$utype = 'agent';
				$dblavel = ['partner'=>$authid];

			}

			// elseif($user_type=='dso') {

			// 	$utype = 'agent';
			// 	$up_partner = auth()->user('web')->partner;
			// 	$dblavel = ['dso'=>$authid, 'partner'=>$up_partner];

			// }

            // else {

			//     $utype = 'agent';
			// 	$up_partner = auth()->user('web')->partner;
			// 	$dblavel = ['dso'=>$authid, 'partner'=>$up_partner];
			// }

			$member_code = rand(1111,9999);

			$password =$request->password;

			$passinterval=30;

		 $usercreate = User::create([
            'fullname' => $request->fullname,
            'member_code' => $member_code,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($password),
            'user_type' => $utype,
            'create_by' => $authid,
            'status' => 1,
			'pass_expire' => date('Y-m-d',strtotime(date("Y-m-d"). "+$passinterval days")),
			'pin_expire' => date('Y-m-d',strtotime(date("Y-m-d"). "+$passinterval days")),
            'email_verification_token' => Str::random(32)
        ]+ $dblavel);


		 $mlMessage = "Dear ".$request->fullname. " Thanks for your registration in Your " . $utype. " ID is : " . $member_code . " password is : " . $password . " And Login Mobile is : " . $request->mobile . "";

		 if(($usercreate)){

            DB::commit();

		 }else {
			  DB::rollback();
		 }

		  Session::flash('message', translate('member_created_successfully').' '.$mlMessage);

		  return redirect('/member_list')->with('message', $mlMessage);



	}

	public function member_list(Request $request)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '10';

		$authid = auth()->user('web')->id;

        $query_data = User::where('db_status', 'live')->where('create_by', $authid);

        if(!empty($request->member_code)) {
            $query_data->where('member_code', $request->member_code);
        }

        if(!empty($request->get('message'))) {
            $query_data->where('fullname', 'LIKE','%'.$request->get('message').'%')
            ->orWhere('email', 'LIKE','%'.$request->get('message').'%')
            ->orWhere('mobile', 'LIKE','%'.$request->get('message').'%');
        }

        $data = $query_data->paginate($rows);

        if ($request->ajax()) {
            return view('member.agent.agent_data', compact('data'));
        }
        return view('member.agent.agent_list', ['data' => $data]);
    }

	public function member_edit($id)
    {
        $user = User::find($id);
        return view('member.agent.agent_edit_form', ['user' => $user]);
    }

    public function member_update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|max:55',
            'email' => 'required',
            'mobile' => 'required',
            //'password' => 'required',
        ]);

        $user = User::find($id);
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        if($request->password) {
            $user->password = $request->password;
        }
        $user->save();

        return redirect('/member_list')->with('message', 'Data Update Success');
    }


    public function member_delete($id)
    {

		$user = User::find($id);
		$user->db_status = 'deleted';
		$user->save();
        //User::where('user_type', 'partner')->where('id', $id)->delete();

        return response()->json(['message' => " success "], 200);
    }
}
