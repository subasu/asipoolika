<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendTicketValidation;
use App\Http\Requests\ServiceRequestValidation;
use App\Models\Conversation;
use App\Models\RequestType;
use App\Models\Ticket;
use App\Models\Unit;
use App\Models\UnitCount;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
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

    /* shiri
       below function is related to get all units from data base and send to view
    */
    public function getUnits()
    {
        $units = Unit::all();
        return response()->json($units);
    }

    /* shiri
       below function is register ticket request
     *  */
    public function sendTicket(SendTicketValidation $request)
    {
        $reciverId = Unit::where('title',$request->unit)->value('supervisor_id');
        $unitId    = Unit::where('title',$request->unit)->value('id');
        $now  = new Carbon();
        $date = $now->toDateString();
        $time = $now->toTimeString();
        $query = DB::table('tickets')->insert
        ([
           'title'           => $request->title,
           'description'     => $request->description,
           'date'            => $date,
           'time'            => $time,
           'unit_id'         => $unitId,
           'sender_user_id'  => Auth::user()->id,
           'reciver_user_id' => $reciverId
        ]);
        if($query)
        {
            return response('اطلاعات شما با موفقیت ثبت گردید');
        }else
            {
                return response('خطایی در ثبت اطلاعات رخ داده است،لطفا با واححد پشتیبانی تماس بگیرید');
            }

    }

    //shiri : below function is to return all tickets to the view to check the status
    public function ticketsManagement()
    {
        $pageTitle = 'بررسی تیکت ها';
        $userId = Auth::user()->id;
        $tickets = Ticket::where('sender_user_id' , $userId)->get();
        foreach ($tickets as $ticket)
        {
            $ticket->date = $this->toPersian($ticket->date);
        }
        return view ('user.ticketsManagement',compact('tickets' , 'pageTitle'));
    }



    //below function is to convert to jalali
    public function toPersian($date)
    {
        $gDate = $date;
        if ($date = explode('-', $gDate)) {
            $year  = $date[0];
            $month = $date[1];
            $day   = $date[2];
        }
        $date = Verta::getJalali($year, $month, $day);
        $myDate = $date[0] . '/' . $date[1] . '/' . $date[2];
        return $myDate;
    }


    //shiri : below function is related to search on date
    public function searchOnDate(Request $request,$id)
    {
        $userId = Auth::user()->id;
        $date1 = trim($request->date1);
        if ($dat1 = explode('/', $date1)) {
            $year = $dat1[0];
            $month = $dat1[1];
            $day = $dat1[2];
            $gDate1 = $this->jalaliToGregorian($year, $month, $day);
        }
        $gDate1 = $gDate1[0] . '-' . $gDate1[1] . '-' . $gDate1[2];

        /***** give second  jalali date and convert it to gregorian date *****/
        $date2 = trim($request->date2);
        if ($dat2 = explode('/', $date2)) {
            $year = $dat2[0];
            $month = $dat2[1];
            $day = $dat2[2];
            $gDate2 = $this->jalaliToGregorian($year, $month, $day);
        }
        $gDate2 = $gDate2[0] . '-' . $gDate2[1] . '-' . $gDate2[2];

        switch ($id) {
            case 1 :
                $data = Ticket::whereBetween('date', [$gDate1, $gDate2])->where([['active', 0], ['sender_user_id', $userId]])->orderBy('date')->get();
                break;
        }
        foreach ($data as $date) {
            $date->date = $this->toPersian($date->date);
        }
        return response()->json(compact('data'));
    }



    //shiri : below function is related to convert jalali date to gregorian date
    public function jalaliToGregorian($year, $month, $day)
    {
        return Verta::getGregorian($year, $month, $day);
    }

    //shiri : below function is related to show conversations of ticket and somehow it's details
    public function ticketConversation($id)
    {
        //
        $pageTitle = 'مشاهده جزییات تیکت ها';
        $conversations = Conversation::where('ticket_id',$id)->get();
        return view ('user.ticketConversation',compact('conversations','pageTitle'));

    }
}
