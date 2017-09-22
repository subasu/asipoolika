<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestRecord;
class CertificateController extends Controller
{
    //active later
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }
    //
    public function showToCreditManager()
    {
        $requests = RequestRecord::where([['step',2],['active',1],['refuse_user_id',null]])->get();
        //dd($request);
        return view('admin.creditManager',compact('requests'));
    }
}
