<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateRecord;
use App\Models\Request2;
use App\Models\Unit;
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
    public function impartGet($id)
    {
        $pageTitle="ابلاغ درخواست شماره : ".$id;
        $request=Request2::find($id);
        if($request->supplier_id!=null)
            return redirect('admin/confirmProductRequestManagement');
        $unit_id=Unit::where('title','تدارکات')->pluck('id');
        $users=User::where([['is_supervisor',0],['unit_id',$unit_id]])->get();
        return view('admin.impart',compact('pageTitle','users','id'));
    }
    public function impart(Request $request)
    {
        if (!$request->ajax())
        {
            abort(403);
        }
        $user_id=$request->user;
        $request_id=$request->request_id;
        $imparted=Request2::where('id',$request_id)->update([
            'supplier_id'=>$user_id
        ]);
        if($imparted)
        {
            $message='درخواست با موفقیت ابلاغ شد';
            $n=1;
        } else
        {
            $message='در ابلاغ درخواست خطایی رخ داده است';
            $n=1;
        }
        return response()->json(compact('message','n'));
    }
    public function execute_certificate(Request $request)
    {
        $record_count=$request->checked_count;
//        return response()->json($request->new_price2);
        if($record_count!=0)
        {
            $certificate_id=DB::table('certificates')->insertGetId([
                'request_id'=>$request->request_id,
                'user_id'=>Auth::user()->id,
                'shop_comp'=>$request->shop_comp,
                'certificate_type_id'=>$request->certificate_type,
                'created_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
            $i=0;
            do{
                $q=DB::table('certificate_records')->insert([
                    'request_record_id'=>$request->record_id[$i],
                    'price'=>$request->new_price2[$i],
                    'rate'=>$request->new_rate[$i],
                    'count'=>$request->new_count[$i],
                    'unit_count'=>$request->unit_count[$i],
                    'certificate_id'=>$certificate_id,
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
    public function certificatesManagementGet()
    {
        $pageTitle='مدیریت گواهی ها';
        $pageName='certificateManagement';

        $user=Auth::user();
        if($user->is_supervisor==0)
        {
            $unit_id=Unit::where('title','تدارکات')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $my_roll='supplier';
                $step=3;
                $me='من کارپردازم';
            }
            else
            {
                $my_roll='unit_employee';
                $step=1;
                $me='من کارمند جز واحدم';
            }

        }
        elseif($user->is_supervisor==1)
        {
            $unit_id=Unit::where('title','ریاست')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $my_roll='boss';
                $step=4;
                $me='من رئیسم';
            }
            else
            {
                $my_roll='unit_supervisor';
                $step=2;
                $me='من مدیر واحدم';
            }
        }
        else $my_roll='non';

        switch($my_roll) {
            case 'supplier':
                $request_id = Request2::where('supplier_id', $user->id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where('step', 3)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->get();
//                dd($certificates);
                break;
            case 'unit_employee':
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where('step', 1)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->get();
//                dd($user->id);
                break;
            case 'boss':
                $certificate_id = CertificateRecord::where('step', 4)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_id)->get();
                break;
            case 'unit_supervisor':
                //bring certificates as a unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where('step', 2)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->get();

                //bring certificates as a unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where('step', 1)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates2 = Certificate::whereIn('id', $certificate_records)->get();
                $certificates=$certificates->merge($certificates2);

//                dd($certificates);
                break;
        }
//        dd($me.' / '.$step.' / '.$my_roll);
        return view('admin.certificate.certificateManagement',compact('pageTitle','pageName','certificates','title','certificates2'));
    }
    public function certificateRecordsGet($id)
    {
       $pageTitle='مدیریت رکوردهای گواهی شماره : '.$id;
        $user=Auth::user();
        if($user->is_supervisor==0)
        {
            $unit_id=Unit::where('title','تدارکات')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $step=3;
                $my_roll='supplier';
                $me='من کارپردازم';
            }
            else
            {
                $step=1;
                $my_roll='unit_employee';
                $me='من کارمند جز واحدم';
            }
        }
        elseif($user->is_supervisor==1)
        {
            $unit_id=Unit::where('title','ریاست')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $step=4;
                $my_roll='boss';
                $me='من رئیسم';
            }
            else
            {
                $step=2;
                $my_roll='unit_supervisor';
                $me='من مدیر واحدم';
            }
        }
        else $step=0;
        switch($my_roll) {
            case 'supplier':
                $request_id = Request2::where('supplier_id', $user->id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords = CertificateRecord::where('step', 3)->whereIn('certificate_id', $certificate_id)->get();

//                dd($certificates);
                break;
            case 'unit_employee':
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords = CertificateRecord::where('step', 1)->whereIn('certificate_id', $certificate_id)->get();
//                dd($certificate_records);
                break;
            case 'boss':
                $certificateRecords = CertificateRecord::where('step', 4)->get();
                break;
            case 'unit_supervisor':
                //bring certificates as a unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords = CertificateRecord::where('step', 2)->whereIn('certificate_id', $certificate_id)->get();

                //bring certificates as a unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records2 = CertificateRecord::where('step', 1)->whereIn('certificate_id', $certificate_id)->get();

                $certificateRecords=$certificateRecords->merge($certificate_records2);
//                dd($certificate_records);
                break;
        }
//        $certificate_records=CertificateRecord::where([['certificate_id',$id],['step',$step]])->get();

       return view('admin.certificate.certificateRecords',compact('pageTitle','certificateRecords','certificates'));
    }
    public function acceptCertificate(Request $request)
    {
        if (!$request->ajax())
        {
            abort(403);
        }
        $user=Auth::user();
        if($user->is_supervisor==0)
        {
            $unit_id=Unit::where('title','تدارکات')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $step=4;
                $me='من کارپردازم';
            }
            else
            {
                $step=2;
                $me='من کارمند جز واحدم';
            }
        }
        elseif($user->is_supervisor==1)
        {
            $unit_id=Unit::where('title','ریاست')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $step=5;
                $me='من رئیسم';
            }
            else
            {
                $step=3;
                $me='من مدیر واحدم';
            }
        }
        else $step=0;



        $q=CertificateRecord::where('id',$request->certificate_record_id)->update([
            'step'=>$step,
            'active'=>1,
            'updated_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
        ]);
        if($q)
        {
            return response()->json('گواهی با موفقیت تایید شده و به مرحله بعد ارسال شد');
        } else
        return response()->json('خطایی رخ داده است');
    }
}
