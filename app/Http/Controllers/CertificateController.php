<?php

namespace App\Http\Controllers;

use App\Models\Request2;
use App\User;
use Illuminate\Http\Request;
use App\Models\RequestRecord;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    //active later
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showToCreditManager()
    {
        $requests = RequestRecord::where([['step',2],['active',1],['refuse_user_id',null]])->get();
        return view('admin.creditManager',compact('requests'));
    }
    public function deliveryAndInstallCertificateGet($id)
    {
        $pageTitle="صدور گواهی";
        $user=Auth::user();
        $requestRecords=RequestRecord::where([['request_id',$id],['active',1]])->get();
        $users=User::where('unit_id',$requestRecords[0]->request->user->unit->id)->get();
        return view('admin.certificate.certificate',compact('pageTitle','requestRecords','user','users'));
    }
}
