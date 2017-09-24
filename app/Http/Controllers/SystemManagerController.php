<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Models\Unit;
use App\Models\UnitCount;
use App\User;
use Illuminate\Http\Request;

class SystemManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //Kianfar : return signature list view
    public function getSignatures()
    {
        $pageTitle="مدیریت امضاها";
        $signatures=Signature::all();
        return view('system_manager.signature',compact('signatures','pageTitle'));
    }
    //Kianfar : return add new signature view
    public function getAddSignature()
    {
        $pageTitle="درج امضای جدید";
        $units=Unit::where('active',1)->get();
        return view('system_manager.add_signature',compact('units','pageTitle'));
    }
    //Kianfar : load user's of the unit that has been selected in add signature view
    public function unit_user_list(Request $request)
    {
        if (!$request->ajax())
        {
            abort(403);
        }
        $unit_id=$request->unit_id;
        $users=User::where('unit_id',$unit_id)->get();
        return response()->json(compact('users'));
    }
    public function getEditSignature($id)
    {
        $signature_info=Signature::where('id',$id)->get();
        $units=Unit::where('active',1)->get();
//        dd($signature_info,$units);
        return view('system_manager.edit_signature',compact('signature_info','units'));
    }
}
