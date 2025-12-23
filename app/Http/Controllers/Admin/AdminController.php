<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //

	public function dashboard() {
        return view('admin.admin-dashboard');
    }

    public function reset_balance(){
        allBalanceZero();

        return redirect()->back();
    }

}
