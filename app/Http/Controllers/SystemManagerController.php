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
    public function getSignature()
    {
        $signatures=Signature::all();
        return view('system_manager.signature',compact('signatures'));
    }
    public function getAddSignature()
    {
        $units=Unit::where('active',1)->get();
        return view('system_manager.add_signature',compact('units'));
    }
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
}
