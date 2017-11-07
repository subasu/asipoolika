<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\User;
use Carbon\Carbon;
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
    public function productRequest(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }
//        return response()->json($request->all());

        $record_count = $request->record_count;

        //it has some records at least one record
//        return response()->json($record_count);
        if ($record_count != 0) {
            $request_id = DB::table('requests')->insertGetId([
                'request_type_id' => 3,
                'user_id' =>$request->user_id2,
                'unit_id' =>$request->unit_id2,
                'supplier_id'=>$request->supplier_id2,
                'active'=>1,
                'created_at' => Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
            $i = 0;
//            return response()->json($request->product_receiver[$i]);

            do {
                if($request->product_receiver[$i]==9)
                    $step=9;
                else $step=7;
                $q = DB::table('request_records')->insert([
                    'title' => $request->product_title[$i],
                    'price'=> $request->product_price[$i],
                    'rate'=> $request->product_rate[$i],
                    'description' => $request->product_details[$i],
                    'code' => mt_rand(1000, 5000),
                    'count' => $request->product_count[$i],
                    'unit_count' => $request->unit_count_each[$i],
                    'step' =>$step,
                    'accept'=>1,
                    'receiver_id'=>$request->product_receiver[$i],
                    'request_id' => $request_id,
                    'active'=>1,
                    'created_at' => Carbon::now(new \DateTimeZone('Asia/Tehran'))
                ]);
                $i++;
                $record_count--;
            } while ($record_count >= 1);

            return response()->json($q);
        } else
            return response()->json('is zero');
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

        $record_count = $request->record_count;

        //it has some records at least one record
//        return response()->json($record_count);
        if ($record_count != 0) {
            $request_id = DB::table('requests')->insertGetId([
                'request_type_id' => 2,
                'user_id' =>$request->user_id2,
                'unit_id' =>$request->unit_id2,
                'supplier_id'=>$request->supplier_id2,
                'active'=>1,
                'created_at' => Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
            $i = 0;

            do {
                $q = DB::table('request_records')->insert([
                    'title' => $request->product_title[$i],
                    'price'=> $request->product_price[$i],
                    'rate'=> $request->product_rate[$i],
                    'description' => $request->product_details[$i],
                    'code' => mt_rand(1000, 5000),
                    'count' => $request->product_count[$i],
                    'step' =>7,
                    'accept'=>1,
                    'receiver_id'=>$request->product_receiver[$i],
                    'request_id' => $request_id,
                    'active'=>1,
                    'created_at' => Carbon::now(new \DateTimeZone('Asia/Tehran'))
                ]);
                $i++;
                $record_count--;
            } while ($record_count >= 1);

            return response()->json($q);
        } else
            return response()->json('is zero');
    }
}

