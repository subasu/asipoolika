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

    public function execute_certificateGet($id)
    {

        $pageTitle="صدور گواهی";
        $user=Auth::user();
        $requestRecords=RequestRecord::where([['request_id',$id],['active',1],['step',7]])->get();
        if(empty($requestRecords[0]))
        {
            return redirect('admin/confirmedRequestDetails/'.$id);
        }
        $users=User::where('unit_id',$requestRecords[0]->request->unit_id)->get();

       foreach($requestRecords as $requestRecord)
       {
           //decrypt
           if(!empty($requestRecord->title))
               $requestRecord->title=decrypt($requestRecord->title);
           if(!empty($requestRecord->description))
               $requestRecord->description=decrypt($requestRecord->description);
           if(!empty($requestRecord->unit_count))
               $requestRecord->unit_count=decrypt($requestRecord->unit_count);
           if(!empty($requestRecord->price))
               $requestRecord->price=decrypt($requestRecord->price);
           if(!empty($requestRecord->rate))
               $requestRecord->rate=decrypt($requestRecord->rate);
           if(!empty($requestRecord->why_not))
               $requestRecord->why_not=decrypt($requestRecord->why_not);
       }
        return view('admin.certificate.certificate',compact('pageTitle','requestRecords','user','users'));
    }

    public function execute_certificate(Request $request)
    {
        $newPriceArray  = explode(',',$request->newPrice);
        $newRateArray   = explode(',',$request->newRate);
        $recordIdArray  = explode(',',$request->recordId);
        $newCountArray  = explode(',',$request->newCount);
        $unitCountArray = explode(',',$request->unitCount);
        $len            = count($recordIdArray);
        //dd($len);
        //$record_count=$request->checked_count;
        if($len!=0)
        {
            $certificate_id=DB::table('certificates')->insertGetId([
                'request_id'          => $request->requestId,
                'user_id'             => Auth::user()->id,
                'shop_comp'           => encrypt($request->shop_comp),
                'certificate_type_id' => $request->certificateType,
                'created_at'          => Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
            $i=0;
            while($i < $len-1)
            {
                $q=DB::table('certificate_records')->insert([

                    'request_record_id' => $recordIdArray[$i],
                    'price'             =>  encrypt($newPriceArray[$i]),
                    'rate'              =>  encrypt($newRateArray[$i]),
                    'count'             => $newCountArray[$i],
                    'unit_count'        =>  encrypt($unitCountArray[$i]),
                    'certificate_id'    => $certificate_id,
                    'receiver_id'       => $request->receiverId,
                ]);
                DB::table('request_records')->where('id',$recordIdArray[$i])->update([
                    'step'=>8,
                    'receiver_id'=>$request->receiverId,
                    'updated_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
                ]);
                $i++;
                //$record_count--;
            }

            if($q)
                return response('inserted');
        }
        //return response($record_count);
    }

    public function deliver_to_store(Request $request)
    {
        $newPriceArray  = explode(',',$request->newPrice);
        $newRateArray   = explode(',',$request->newRate);
        $recordIdArray  = explode(',',$request->recordId);
        $newCountArray  = explode(',',$request->newCount);
        $unitCountArray = explode(',',$request->unitCount);
        $len            = count($recordIdArray);
        //dd($len);
        //$record_count=$request->checked_count;
        $unit_id=Unit::where('title','انبار')->pluck('id');
        $receiver_id=User::where([['unit_id',$unit_id[0],['is_supervisor',1]]])->pluck('id');

        if($len!=0)
        {
            $certificate_id=DB::table('certificates')->insertGetId([
                'request_id'          => $request->requestId,
                'user_id'             => Auth::user()->id,
                'shop_comp'           => $request->shop_comp,
                'certificate_type_id' => $request->certificateType,
                'active'=>1,
                'created_at'          => Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
            $i=0;
            while($i < $len-1)
            {
                $q=DB::table('certificate_records')->insert([

                    'request_record_id' => $recordIdArray[$i],
                    'price'             => $newPriceArray[$i],
                    'rate'              => $newRateArray[$i],
                    'count'             => $newCountArray[$i],
                    'unit_count'        => $unitCountArray[$i],
                    'certificate_id'    => $certificate_id,
                    'receiver_id'       => $receiver_id[0],
                    'active'            =>1,
                    'step'              =>5
                ]);
                DB::table('request_records')->where('id',$recordIdArray[$i])->update([
                    'receiver_id'=>$receiver_id[0],
                    'step'=>9,
                    'updated_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
                ]);
                $i++;
                //$record_count--;
            }
            if($q)
                return response('inserted');
        }
        //return response($record_count);
    }


    public function productCertificatesManagementGet()
    {
        $pageTitle='مدیریت گواهی های کالا';
        $pageName='productCertificateManagement';

        $user=Auth::user();
        if($user->is_supervisor==0)
        {
            $unit_id=Unit::where('title','تدارکات')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $my_roll='supplier';
                $step=3;
                $step2=4;
                $me='من کارپردازم';
            }
            else
            {
                $my_roll='unit_employee';
                $step=1;
                $step2=2;
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
                $step2=5;
                $me='من رئیسم';
            }
            else
            {
                $my_roll='unit_supervisor';
                $step=2;
                $step2=3;
                $me='من مدیر واحدم';
            }
        }
        else $my_roll='non';

        switch($my_roll) {
            case 'supplier':
                //as supplier
                $request_id = Request2::where('supplier_id', $user->id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where('step', 3)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();

                //as a unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where([['receiver_id',$user->id],['step', 1]])->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates2 = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();
                $certificates=$certificates->merge($certificates2);
//                dd($certificates);
                break;
            case 'unit_employee':
                $request_id = RequestRecord::where([['receiver_id', $user->id],['step',8]])->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where([['receiver_id',$user->id],['step', 1]])->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();
//                dd($certificate_id);
                break;
            case 'boss':
                $certificate_id = CertificateRecord::where('step', 4)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_id)->orderBy('created_at','desc')->get();
                //as a unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where([['receiver_id',$user->id],['step', 1]])->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates2 = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();
                $certificates=$certificates->merge($certificates2);
                break;
            case 'unit_supervisor':
                //bring certificates as a unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where('step', 2)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();

                //bring certificates as a unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where([['receiver_id',$user->id],['step', 1]])->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates2 = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();
                $certificates=$certificates->merge($certificates2);

//                dd($certificates);
                break;
        }
        foreach($certificates as $certificate)
        {
            //undecided records
            $certificate->certificate_record_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',$step]])->count();
            //in the process records
            $certificate->certificate_record_count_accept=CertificateRecord::where([['certificate_id',$certificate->id],['step','>=',$step2],['active',1]])->count();
            $certificate->request_type=$certificate->request->requestType->title;
        }
//        dd($me.' / '.$step.' / '.$my_roll);
        return view('admin.certificate.certificateManagement',compact('pageTitle','pageName','certificates','title','certificates2'));
    }

    public function serviceCertificatesManagementGet()
    {
        $pageTitle='مدیریت گواهی های خدمت';
        $pageName='serviceCertificateManagement';

        $user=Auth::user();
        if($user->is_supervisor==0)
        {
                $my_roll='unit_employee';
                $step=1;
                $step2=2;
                $me='من کارمند جز واحدم';
        }
        elseif($user->is_supervisor==1)
        {
            $unit_id=Unit::where('title','ریاست')->pluck('id');
            $unit_id_t=Unit::where('title','تدارکات')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $my_roll='boss';
                $step=4;
                $step2=5;
                $me='من رئیسم';
            }
            elseif($user->unit_id==$unit_id_t[0])
            {
                $my_roll='supply_supervisor';
                $step=3;
                $step2=4;
                $me='من مدیر تدارکاتم';
            }
            else
            {
                $my_roll='unit_supervisor';
                $step=2;
                $step2=3;
                $me='من مدیر واحدم';
            }
        }
        else $my_roll='non';

        switch($my_roll) {
            case 'supply_supervisor':
                //as supply supervisor
                $certificate_id = CertificateRecord::where('step', 3)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_id)->orderBy('created_at','desc')->get();
                //as unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where('step', 2)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates2 = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();

                //as receiver
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where([['receiver_id',$user->id],['step', 1]])->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates3 = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();

                $certificates=$certificates->merge($certificates2);
                $certificates=$certificates->merge($certificates3);
//                dd($certificates);
                break;
            case 'unit_employee':
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where([['receiver_id',$user->id],['step', 1]])->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();
//                dd($user->id);
                break;
            case 'boss':
                //as boss
                $certificate_id = CertificateRecord::where('step', 4)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_id)->orderBy('created_at','desc')->get();
                //as receiver
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where([['receiver_id',$user->id],['step', 1]])->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates2 = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();

                //bring certificates as a unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where('step', 2)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates3 = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();
                $certificates=$certificates->merge($certificates2);
                $certificates=$certificates->merge($certificates3);
                break;
            case 'unit_supervisor':
                //bring certificates as a unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where('step', 2)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();

                //bring certificates as a unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records = CertificateRecord::where([['receiver_id',$user->id],['step', 1]])->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates2 = Certificate::whereIn('id', $certificate_records)->orderBy('created_at','desc')->get();
                $certificates=$certificates->merge($certificates2);

//                dd($certificates);
                break;
        }
        foreach($certificates as $certificate)
        {
            //undecided records
            $certificate->certificate_record_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',$step]])->count();
            //in the process records
            $certificate->certificate_record_count_accept=CertificateRecord::where([['certificate_id',$certificate->id],['step','>=',$step2],['active',1]])->count();
            $certificate->request_type=$certificate->request->requestType->title;
        }
//        dd($me.' / '.$step.' / '.$my_roll);
        return view('admin.certificate.certificateManagement',compact('pageTitle','pageName','certificates','title','certificates2'));
    }

    public function productCertificateRecordsGet($id)
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
                //as supplies
                $request_id = Request2::where('supplier_id', $user->id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords = CertificateRecord::where([['step', 3],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();
                //as unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords2 = CertificateRecord::where([['step', 1],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();
                $certificateRecords=$certificateRecords->merge($certificateRecords2);
//                dd($certificateRecords);
                break;
            case 'unit_employee':
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords = CertificateRecord::where([['step', 1],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();
//                dd($certificate_records);
                break;
            case 'boss':
                $certificateRecords = CertificateRecord::where([['step', 4],['certificate_id',$id]])->get();
                //as unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords2 = CertificateRecord::where([['step', 1],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();
                $certificateRecords=$certificateRecords->merge($certificateRecords2);
                break;
            case 'unit_supervisor':
                //bring certificates as a unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords = CertificateRecord::where([['step',2],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();

                //bring certificates as a unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records2 = CertificateRecord::where([['step', 1],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();

                $certificateRecords=$certificateRecords->merge($certificate_records2);
//                dd($certificate_records);
                break;
        }
        foreach($certificateRecords as $certificateRecord)
        {
//decrypt
            if(!empty($certificateRecord->unit_count))
                $certificateRecord->unit_count=decrypt($certificateRecord->unit_count);
            if(!empty($certificateRecord->price))
                $certificateRecord->price=decrypt($certificateRecord->price);
            if(!empty($certificateRecord->rate))
                $certificateRecord->rate=decrypt($certificateRecord->rate);
            if(!empty($certificateRecord->why_not))
                $certificateRecord->why_not=decrypt($certificateRecord->why_not);
        }

//        $certificate_records=CertificateRecord::where([['certificate_id',$id],['step',$step]])->get();

       return view('admin.certificate.certificateRecords',compact('pageTitle','certificateRecords','certificates'));
    }

    public function serviceCertificateRecordsGet($id)
    {
        $pageTitle='مدیریت رکوردهای گواهی شماره : '.$id;
        $user=Auth::user();
        if($user->is_supervisor==0)
        {
            $step=1;
            $my_roll='unit_employee';
            $me='من کارمند جز واحدم';
        }
        elseif($user->is_supervisor==1)
        {
            $unit_id=Unit::where('title','ریاست')->pluck('id');
            $unit_id_t=Unit::where('title','تدارکات')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $step=4;
                $my_roll='boss';
                $me='من رئیسم';
            }
            elseif($user->unit_id==$unit_id_t[0])
            {
                $my_roll='supply_supervisor';
                $step=3;
                $me='من مدیر تدارکاتم';
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
            case 'supply_supervisor':
                //as supply supervisor
                $certificateRecords = CertificateRecord::where([['step', 3],['certificate_id',$id]])->get();
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                //as unit supervisor
                $certificateRecords2 = CertificateRecord::where([['step',2],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();

                //as receiver
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords3 = CertificateRecord::where([['step', 1],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();
                $certificateRecords=$certificateRecords->merge($certificateRecords2);
                $certificateRecords=$certificateRecords->merge($certificateRecords3);
//                dd($certificateRecords);
                break;
            case 'unit_employee':
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords = CertificateRecord::where([['step', 1],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();
//                dd($certificate_records);
                break;
            case 'boss':
                //as boss
                $certificateRecords = CertificateRecord::where([['step', 4],['certificate_id',$id]])->get();
                //bring certificates as a unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords2 = CertificateRecord::where([['step',2],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();
                $certificateRecords=$certificateRecords->merge($certificateRecords2);
                //as receiver
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords3 = CertificateRecord::where([['step', 1],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();

                $certificateRecords=$certificateRecords->merge($certificateRecords3);
                break;
            case 'unit_supervisor':
                //bring certificates as a unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificateRecords = CertificateRecord::where([['step',2],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();

                //bring certificates as a unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
                $certificate_records2 = CertificateRecord::where([['step', 1],['certificate_id',$id]])->whereIn('certificate_id', $certificate_id)->get();

                $certificateRecords=$certificateRecords->merge($certificate_records2);
                foreach($certificateRecords as $certificateRecord)
                {
//decrypt
                    if(!empty($certificateRecord->unit_count))
                        $certificateRecord->unit_count=decrypt($certificateRecord->unit_count);
                    if(!empty($certificateRecord->price))
                        $certificateRecord->price=decrypt($certificateRecord->price);
                    if(!empty($certificateRecord->rate))
                        $certificateRecord->rate=decrypt($certificateRecord->rate);
                    if(!empty($certificateRecord->why_not))
                        $certificateRecord->why_not=decrypt($certificateRecord->why_not);
                }

                break;
        }
//        $certificate_records=CertificateRecord::where([['certificate_id',$id],['step',$step]])->get();
//dd($certificateRecords);
        return view('admin.certificate.certificateRecords',compact('pageTitle','certificateRecords','certificates'));
    }

    public function acceptProductCertificate(Request $request)
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
                //I am the supplier also I am the receiver
                $request_record_id=CertificateRecord::where('id',$request->certificate_record_id)->pluck('request_record_id');
                $receiver_id=RequestRecord::where('id',$request_record_id)->pluck('receiver_id');
                if($user->id==$receiver_id[0])
                    $step=2;
                //The receiver is also the BOSS
                $user_unit_id=User::where('id',$receiver_id[0])->pluck('unit_id');
                if($user_unit_id[0]==4)
                    $step=5;

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
                $active=1;
                $me='من رئیسم';
                $request_record_id=CertificateRecord::where('id',$request->certificate_record_id)->pluck('request_record_id');
                //چه کسی تحویل گیرنده بوده
                $receiver_id=RequestRecord::where('id',$request_record_id)->pluck('receiver_id');
                if($user->id==$receiver_id[0])
                    $step=3;
            }
            else
            {
                $step=3;
                $me='من مدیر واحدم';
                $request_record_id=CertificateRecord::where('id',$request->certificate_record_id)->pluck('request_record_id');
                //چه کسی تحویل گیرنده بوده
                $receiver_id=RequestRecord::where('id',$request_record_id)->pluck('receiver_id');
                //چه کسی کارپرداز بوده
                $request_id=RequestRecord::where('id',$request_record_id)->pluck('request_id');
                $supplier_id=Request2::where('id',$request_id[0])->pluck('supplier_id');
                if($receiver_id==$supplier_id)
                    $step=4;
            }
        }
        else $step=0;$active=0;
//return response($me);
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

    public function acceptServiceCertificate(Request $request)
    {
        if (!$request->ajax())
        {
            abort(403);
        }
        $user=Auth::user();

        if($user->is_supervisor==0)
        {
            $step=2;
            $me='من کارمند جز واحدم';

        }
        elseif($user->is_supervisor==1)
        {
            $unit_id=Unit::where('title','ریاست')->pluck('id');
            $unit_id_t=Unit::where('title','تدارکات')->pluck('id');

            if($user->unit_id==$unit_id[0])
            {
                $step=5;
                $me='من رئیسم';
                $request_record_id=CertificateRecord::where('id',$request->certificate_record_id)->pluck('request_record_id');
                $receiver_id=RequestRecord::where('id',$request_record_id[0])->pluck('receiver_id');
//اگر تحویل گیرنده همان رئیس سازمان است پس مدیر واحد نیز هست پس از تایید وی کافیست به مرحله 3 برود
                if($user->id==$receiver_id[0])
                    $step=3;
//                return response($request_id);
//                $unit_id=Request2::where('id',$request_id[0])->pluck('unit_id');

//                if($unit_id[0]==6)
//                    $step=6;

            }
            elseif($user->unit_id==$unit_id_t[0])
            {
                $step=4;
                $me='من مدیر تدارکاتم';
                //اگر درخواست کننده رئیس سازمان باشد دیگر نیازی نیست که به مرحله 4 برورد چون رئیس سازمان یکبار در مرحله تحویل گیرنده و مسئول واجد تایید را انجام داده است
                $request_record_id=CertificateRecord::where('id',$request->certificate_record_id)->pluck('request_record_id');
                $receiver_id=RequestRecord::where('id',$request_record_id[0])->pluck('receiver_id');
                $receiver_unit_id=User::where('id',$receiver_id)->pluck('unit_id');
                if($receiver_unit_id[0]==4)
                    $step=5;
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

    public function acceptedCertificatesManagementGet()
    {
        $pageTitle='مدیریت گواهی های تایید شده';
        $pageName='acceptedCertificateManagement';

        $user=Auth::user();
        if($user->is_supervisor==0)
        {
            $unit_id=Unit::where('title','تدارکات')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $my_roll='supplier';
                $step=4;
                $step2=3;
                $me='من کارپردازم';
            }
            else
            {
                $my_roll='unit_employee';
                $step=2;
                $step2=1;
                $me='من کارمند جز واحدم';
            }
        }
        elseif($user->is_supervisor==1)
        {
            $unit_id=Unit::where('title','ریاست')->pluck('id');
            if($user->unit_id==$unit_id[0])
            {
                $my_roll='boss';
                $step=5;
                $step2=4;
                $me='من رئیسم';
            }
            else
            {
                $my_roll='unit_supervisor';
                $step=3;
                $step2=2;
                $me='من مدیر واحدم';
            }
        }
        else $my_roll='non';

        switch($my_roll) {
            case 'supplier':
                $request_id = Request2::where('supplier_id', $user->id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
//                $certificate_records = CertificateRecord::where('step', 4)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificate_records = CertificateRecord::where('step','>', 3)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->where('certificate_type_id','!=',4)->orderBy('created_at','desc')->get();
//                dd($certificates);
                break;
            case 'unit_employee':
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
//                $certificate_records = CertificateRecord::where('step', 2)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificate_records = CertificateRecord::where('step','>', 1)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->where('certificate_type_id','!=',4)->orderBy('created_at','desc')->get();
//                dd($user->id);
                break;
            case 'boss':
//                $certificate_id = CertificateRecord::where('step', 5)->pluck('certificate_id');
                $certificate_id = CertificateRecord::where('step','>',4)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_id)->where('certificate_type_id','!=',4)->orderBy('created_at','desc')->get();
                break;
            case 'unit_supervisor':
                //bring certificates as a unit supervisor
                $request_id = Request2::where('unit_id', $user->unit_id)->pluck('id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
//                $certificate_records = CertificateRecord::where('step', 3)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificate_records = CertificateRecord::where('step', '>',2)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates = Certificate::whereIn('id', $certificate_records)->where('certificate_type_id','!=',4)->orderBy('created_at','desc')->get();

                //bring certificates as a unit employee
                $request_id = RequestRecord::where('receiver_id', $user->id)->pluck('request_id');
                $certificate_id = Certificate::whereIn('request_id', $request_id)->pluck('id');
//                $certificate_records = CertificateRecord::where('step', 2)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificate_records = CertificateRecord::where('step','>', 1)->whereIn('certificate_id', $certificate_id)->pluck('certificate_id');
                $certificates2 = Certificate::whereIn('id', $certificate_records)->where('certificate_type_id','!=',4)->orderBy('created_at','desc')->get();
                $certificates=$certificates->merge($certificates2);

//                dd($certificates);
                break;
        }
        foreach($certificates as $certificate)
        {
            //undecided records
            $certificate->certificate_record_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',$step2]])->count();
            //in the process records
            $certificate->certificate_record_count_accept=CertificateRecord::where([['certificate_id',$certificate->id],['step','>=',$step],['active',1]])->count();
            $certificate->request_type=$certificate->request->requestType->title;

            $certificate_records_count=CertificateRecord::where('certificate_id',$certificate->id)->count();
            $accepted_certificate_record_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',5]])->count();
            if($certificate_records_count==$accepted_certificate_record_count)
            {
                Certificate::where('id',$certificate->id)->update([
                    'active'=>1
                ]);
            }

        }
//        dd($me.' / '.$step.' / '.$my_roll);
        return view('admin.certificate.certificateManagement',compact('pageTitle','pageName','certificates','title','certificates2'));
    }

}
