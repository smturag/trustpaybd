<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocsReportsController extends Controller
{
    public function sim_report(){
        return view('admin.reports.sim_report');
    }

}
