<?php

namespace App\Http\Controllers;

use App\Models\Request2;
use App\User;
use Carbon\Carbon;
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
    public function execute_certificateGet($id)
    {
        $pageTitle="صدور گواهی";
        $user=Auth::user();
        $requestRecords=RequestRecord::where([['request_id',$id],['active',1],['step',7]])->get();
        if(empty($requestRecords[0]))
        {
            return redirect('admin/confirmProductRequestManagement');
        }
        $users=User::where('unit_id',$requestRecords[0]->request->user->unit->id)->get();
        return view('admin.certificate.certificate',compact('pageTitle','requestRecords','user','users'));
    }
    public function execute_certificate(Request $request)
    {

        $record_count=$request->checked_count;
//        return response()->json($request->request_id);
        if($record_count!=0)
        {
            $certificate_id=DB::table('certificates')->insertGetId([
                'request_id'=>$request->request_id,
                'user_id'=>Auth::user()->id,
                'certificate_type_id'=>$request->certificate_type,
                'created_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
            $i=0;
            do{
                $q=DB::table('certificate_records')->insert([
                    'price'=>$request->new_price[$i],
                    'rate'=>$request->new_rate[$i],
                    'count'=>$request->new_count[$i],
                    'certificate_id'=>$certificate_id,
                    'shop_comp'=>$request->shop_comp,
                    'receiver_id'=>$request->receiver_id,
                ]);
                DB::table('request_records')->where('id',$request->record_id[$i])->update([
                    'step'=>8,
                    'receiver_id'=>$request->receiver_id,
                    'updated_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
                ]);
                $i++;
                $record_count--;
            } while($record_count>=1);
            if($q)
                return response('inserted');
        }
        return response($record_count);
    }
}
