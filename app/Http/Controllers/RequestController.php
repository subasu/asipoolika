<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequestValidation;
use App\Models\RequestType;
use App\Models\UnitCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //Kianfar : return add product request view
    public function productRequestGet()
    {
        $pageTitle='درخواست کالا';
        return view('user.productRequest',compact('pageTitle','unit_counts'));
    }
    //Kianfar :  add new request
    public function productRequestPost(Request $request)
    {
        if (!$request->ajax())
        {
            abort(403);
        }
        $record_count=$request->record_count;
        //it has some records at least one record
//        return response()->json($record_count);
        if($record_count!=0)
        {
            $request_id=DB::table('requests')->insertGetId([
               'request_type_id'=>3,
               'user_id'=>Auth::user()->id,
               'created_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
           ]);
            $i=0;
          do{
              $q=DB::table('request_records')->insert([
                  'title'=>$request->product_title[$i],
                  'description'=>$request->product_details[$i],
                  'code'=>mt_rand(1000,5000),
                  'count'=>$request->product_count[$i],
                  'unit_count'=>$request->unit_count[$i],
                  'step'=>1,
                  'request_id'=>$request_id
              ]);
              $i++;
              $record_count--;
          } while($record_count>=1);

        return response()->json($q);
        }
        else
            return response()->json('is zero');

    }
    /*  shiri
      below function is related to register service request
  */
    public function serviceRequestGet()
    {
        $pageTitle = 'درخواست خدمت';
        return view('user.serviceRequest' ,compact('pageTitle'));
    }

    public function sendService(ServiceRequestValidation $request)
    {

        $requestType = RequestType::where('title','درخواست خدمت')->value('id');
        if(!$request->ajax())
        {
            abort(403);
        }
        $len = count($request->count);
        //dd($len);
        if($len !=0)
        {
            $requestId = DB::table('requests')->insertGetId
            ([
                'request_type_id' => $requestType,
                'user_id'        => Auth::user()->id,
                'request_qty'    => $len,
                'created_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);

            if($requestId)
            {

                $i= 0;
                while ($len > 0)
                {
                    $q=DB::table('request_records')->insert
                    ([
                        'title'=>trim($request->title[$i]),
                        'code'=>mt_rand(1000,5000),
                        'count'=>trim($request->count[$i]),
                        //'unit_count'=>$request->unit_count[$i],
                        'step'=>1,
                        'request_id'=>$requestId,
                        'description'=>trim($request->description[$i])
                    ]);
                    $i++;
                    $len--;
                }
                if($q)
                {
                    return response ('اطلاعات شما با موفقیت ثبت گردید');
                }else
                {
                    return response ('در ثبت اطلاعات مشکلی وجود دارد.لطفا با واحد پشتیبانی تماس بگیرید');
                }

            }
        }

    }

    /* shiri
       below function is related to ticket request
    */
    public function ticketRequest()
    {
        $pageTitle = 'درخواست ارسال تیکت';
        return view('user.ticketRequest',compact('pageTitle'));
    }

}
