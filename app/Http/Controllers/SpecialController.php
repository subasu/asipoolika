<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateRecord;
use App\Models\Unit;
use App\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpecialController extends Controller
{
    public function productRequestGet()
    {
        $units=Unit::where('active',1)->get();
        $suppliers=User::where([['unit_id',6],['is_supervisor',0]])->get();
        $pageTitle='درج درخواست کالا بایگانی';
        $pageName='specialProductRequest';
        return view('admin.special.productRequest',compact('pageTitle','pageName','units','suppliers'));
    }
    public function receiverGet(Request $request)
    {
        $receivers=User::where('unit_id',$request->id)->orWhere('unit_id',9)->get();
        foreach($receivers as $item)
        {
            $item->unit_name=Unit::where('id',$item->unit_id)->pluck('title');
        }
        return response()->json(compact('receivers'));
    }
    public function receiverGet2(Request $request)
    {
        $receivers=User::where('unit_id',$request->id)->get();
        foreach($receivers as $item)
        {
            $item->unit_name=Unit::where('id',$item->unit_id)->pluck('title');
        }
        return response()->json(compact('receivers'));
    }

    public function jalaliToGregorian($year, $month, $day)
    {
        return Verta::getGregorian($year, $month, $day);
    }
    public function productRequest(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }

        if(preg_match('#^([0-9]?[0-9]?[0-9]{2}[ /.](0?[1-9]|1[012])[ /.](0?[1-9]|[12][0-9]|3[01]))*$#', $request->date2)) {

            $record_count = $request->record_count;
            $date2 = trim($request->date2);
            if ($date1 = explode('/', $date2)) {
                $year = $date1[0];
                $month = $date1[1];
                $day = $date1[2];
                $gDate1 = $this->jalaliToGregorian($year, $month, $day);
            }
            $gDate1 = $gDate1[0] . '-' . $gDate1[1] . '-' . $gDate1[2];
            //it has some records at least one record
//        return response()->json($record_count);
            if ($record_count != 0) {
                $request_id = DB::table('requests')->insertGetId([
                    'request_type_id' => 3,
                    'user_id' => $request->user_id2,
                    'unit_id' => $request->unit_id2,
                    'supplier_id' => $request->supplier_id2,
                    'active' => 1,
                    'created_at' => $gDate1
                ]);
                $i = 0;
//            return response()->json($request->product_receiver[$i]);

                do {
                    if ($request->product_receiver[$i] == 9)
                        $step = 9;
                    else $step = 7;
                    $q = DB::table('request_records')->insert([
                        'title' => encrypt($request->product_title[$i]),
                        'price' => encrypt(str_replace(',','',$request-> product_price[$i])),
                        'rate' =>  encrypt($request->product_rate[$i]),
                        'description' =>  encrypt($request->product_details[$i]),
                        'code' => encrypt(mt_rand(1000, 5000)),
                        'count' => $request->product_count[$i],
                        'unit_count' =>  encrypt($request->unit_count_each[$i]),
                        'step' => $step,
                        'accept' => 1,
//                        'receiver_id' => $request->product_receiver[$i],
                        'request_id' => $request_id,
                        'active' => 1,
                        'created_at' => $gDate1
                    ]);
                    $i++;
                    $record_count--;
                } while ($record_count >= 1);

                return response()->json($q);
            } else
                return response()->json('is zero');
        }else
        {
            return response()->json('لطفا تاریخ را بطور صحیح وارد کنید، مثلا : 1396/05/01');
        }

    }
    public function unitsGet(Request $request)
    {
        //it gets users of the unit and also the receivers: unit's users and store's users
        if (!$request->ajax())
        {
            abort(403);
        }
        $users=User::where('unit_id',$request->unit_id)->get();
        $receiver_id=User::where('unit_id',$request->unit_id)->orWhere('unit_id',9)->get();
        foreach($receiver_id as $item)
        {
            $item->unit_name=Unit::where('id',$item->unit_id)->pluck('title');
        }
        return response()->json(compact('users','receiver_id'));
    }
    public function serviceRequestGet()
    {
        $units=Unit::where('active',1)->get();
        $suppliers=User::where([['unit_id',6],['is_supervisor',0]])->get();
        $pageTitle='درج درخواست خدمت بایگانی';
        $pageName='specialServiceRequest';
        return view('admin.special.serviceRequest',compact('pageTitle','pageName','units','suppliers'));
    }
    public function serviceRequest(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }


        if (preg_match('#^([0-9]?[0-9]?[0-9]{2}[ /.](0?[1-9]|1[012])[ /.](0?[1-9]|[12][0-9]|3[01]))*$#', $request->date2)) {

            $record_count = $request->record_count;
            $date2 = trim($request->date2);
            if ($date1 = explode('/', $date2)) {
                $year = $date1[0];
                $month = $date1[1];
                $day = $date1[2];
                $gDate1 = $this->jalaliToGregorian($year, $month, $day);
            }
            $gDate1 = $gDate1[0] . '-' . $gDate1[1] . '-' . $gDate1[2];
            //it has some records at least one record
//        return response()->json($record_count);
            if ($record_count != 0) {
                $request_id = DB::table('requests')->insertGetId([
                    'request_type_id' => 2,
                    'user_id' => $request->user_id2,
                    'unit_id' => $request->unit_id2,
                    'supplier_id' => $request->supplier_id2,
                    'active' => 1,
                    'created_at' => $gDate1
                ]);
                $i = 0;

                do {
                    $q = DB::table('request_records')->insert([
                        'title' => encrypt($request->product_title[$i]),
                        'price' => encrypt(str_replace(',','',$request-> product_price[$i])),
                        'rate' => encrypt($request->product_rate[$i]),
                        'description' => encrypt($request->product_details[$i]),
                        'code' => encrypt(mt_rand(1000, 5000)),
                        'count' => $request->product_count[$i],
                        'step' => 7,
                        'accept' => 1,
//                        'receiver_id' => $request->product_receiver[$i],
                        'request_id' => $request_id,
                        'active' => 1,
                        'created_at' => $gDate1
                    ]);
                    $i++;
                    $record_count--;
                } while ($record_count >= 1);

                return response()->json($q);
            } else
                return response()->json('is zero');

        }else
        {
            return response()->json('لطفا تاریخ را بطور صحیح وارد کنید، مثلا : 1396/05/01');
        }

    }
    public function activeCertificatesGet($id)
    {
        Certificate::where('request_id',$id)->update([
            'active'=>1
        ]);
        $certificate_id=Certificate::where('request_id',$id)->pluck('id');
        if(empty($certificate_id))
            return redirect()->back();
        $certificate_records=CertificateRecord::where('certificate_id',$certificate_id)->get();
        foreach ($certificate_records as $certificate_record) {
            CertificateRecord::where('id',$certificate_record->id)->update([
                'active'=>1,
                'step'=>5
            ]);
        }
        return redirect()->back();
    }
}

