<?php

namespace App\Http\Controllers;

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

    public function productRequestGet()
    {
        $pageTitle='درخواست کالا';
        return view('user.productRequest',compact('pageTitle','unit_counts'));
    }
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
}
