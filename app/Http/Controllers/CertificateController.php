<?php

namespace App\Http\Controllers;

use App\Models\Request2;
use App\User;
use Illuminate\Http\Request;
use App\Models\RequestRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function execute_certificate(Request $request)
    {

        $record_count=$request->checked_count;
//        return response()->json($request->certificate_type);
        if($record_count!=0)
        {
            $certificate_id=DB::table('certificates')->insertGetId([
                'request_id'=>3,
                'certificate_type_id'=>Auth::user()->id,
                'created_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
            $i=0;
            do{
                $q=DB::table('request_records')->insert([
                    'title'=>$request->product_title[$i],
                    'description'=>$request->product_details[$i],
                    'code'=>mt_rand(1000,5000),
                    'count'=>$request->product_count[$i],
                    'unit_count'=>$request->unit_count_each[$i],
                    'step'=>1,
                    'request_id'=>$request_id
                ]);
                $i++;
                $record_count--;
            } while($record_count>=1);
        }
        return response($record_count);
    }
}
