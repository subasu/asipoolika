<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendTicketValidation;
use App\Http\Requests\ServiceRequestValidation;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Request2;
use App\Models\RequestRecord;
use App\Models\RequestType;
use App\Models\Ticket;
use App\Models\Unit;
use App\Models\UnitCount;
use App\User;
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
               'unit_id'=>Auth::user()->unit_id,
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

        return response()->json($q);
        }
        else
            return response()->json('is zero');

    }
    public function productRequestFollowGet()
    {
        $pageTitle='مدیریت درخواست های من';
        $pageName='myProductRequests';
        $requests=Request2::where([['user_id',Auth::user()->id],['request_type_id',3]])->orderBy('created_at','desc')->get();
        foreach($requests as $request)
        {
            //undecided records
            $request->request_record_count=RequestRecord::where([['request_id',$request->id],['refuse_user_id',null],['step',1]])->count();
            //in the process records
            $request->request_record_count_accept=RequestRecord::where([['request_id',$request->id],['refuse_user_id',null],['step','>',1],['active',1]])->count();
            //inactive records
            $request->request_record_count_refused=RequestRecord::where([['request_id',$request->id],['refuse_user_id','!=',null]])->count();
        }
        return view('user.requestManagement',compact('pageTitle','pageName','requests'));
    }
    /*  shiri
      below function is related to register service request
  */
    public function serviceRequestGet()
    {
        $pageTitle = 'درخواست خدمت';
        return view('user.serviceRequest' ,compact('pageTitle'));
    }

    public function serviceRequest(Request $request)
    {
//        $requestType = RequestType::where('title','درخواست خدمت')->value('id');
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
                'request_type_id'=>2,
                'user_id'=>Auth::user()->id,
                'unit_id'=>Auth::user()->unit_id,
                'created_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
            $i=0;
            do{
                $q=DB::table('request_records')->insert([
                    'title'=>$request->service_title[$i],
                    'description'=>$request->service_details[$i],
                    'count'=>$request->service_count[$i],
                    'code'=>mt_rand(1000,5000),
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
    public function serviceRequestFollowGet()
    {
        $pageTitle='مدیریت درخواست های من';
        $pageName='myServiceRequests';
        $requests=Request2::where([['user_id',Auth::user()->id],['request_type_id',2]])->orderBy('created_at','desc')->get();
        foreach($requests as $request)
        {
            //undecided records
            $request->request_record_count=RequestRecord::where([['request_id',$request->id],['refuse_user_id',null],['step',1]])->count();
            //in the process records
            $request->request_record_count_accept=RequestRecord::where([['request_id',$request->id],['refuse_user_id',null],['step','>',1],['active',1]])->count();
            //inactive records
            $request->request_record_count_refused=RequestRecord::where([['request_id',$request->id],['refuse_user_id','!=',null]])->count();
        }
        return view('user.requestManagement',compact('pageTitle','pageName','requests'));
    }
    public function myRequestRecordsGet($id)
    {
        $pageTitle='رکوردهای درخواست شماره : '.$id;
        $pageName='';
        $requestRecords=RequestRecord::where('request_id',$id)->get();
        foreach($requestRecords as $requestRecord)
        {
            if($requestRecord->request->request_type_id==3)
            {
                switch($requestRecord->step)
                {
                    case 1: $step='تدارکات'; break;
                    case 2: $step='مسئول انبار'; break;
                    case 3: $step='مسئول اعتبار'; break;
                    case 4: $step='امور عمومی'; break;
                    case 5: $step='ریاست'; break;
                    case 6: $step='امورمالی'; break;
                    case 7: $step='تایید شده'; break;
                    case 8: $step='در حال صدور گواهی'; break;
                    default : $step='نامشخص';
                }
            }
            if($requestRecord->request->request_type_id==2)
            {
                switch($requestRecord->step)
                {
                    case 1: $step='تدارکات'; break;
                    case 2: $step='مسئول اعتبار'; break;
                    case 3: $step='امور عمومی'; break;
                    case 4: $step='ریاست'; break;
                    case 5: $step='مسئول واحد'; break;
                    case 6: $step='امورمالی'; break;
                    case 7: $step='تایید شده'; break;
                    case 8: $step='در حال صدور گواهی'; break;
                    default : $step='نامشخص';
                }
            }

            if($requestRecord->refuse_user_id!=null)
            {
                $requestRecord->refuse=1;
                $unit_id=User::where('id',$requestRecord->refuse_user_id)->pluck('unit_id');
                $unit=Unit::where('id',$unit_id[0])->pluck('title');
                $requestRecord->refuseBy=$unit[0];
            }
            else
            {
                $requestRecord->refuse=0;
                $requestRecord->refuseBy='';
            }

            $requestRecord->status=$step;

        }

//        dd($requestRecords[0]->request->user_id);
//        if($requestRecords[0]->request->user_id==Auth::user()->id)
//        {
            return view('user.requestRecords',compact('pageTitle','requestRecords'));
//        }
//        else
//            return back();
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
        $receiverId = Unit::where('title',$request->unit)->value('supervisor_id');
        $unitId    = Unit::where('title',$request->unit)->value('id');
        $now  = new Carbon();
        $date = $now->toDateString();
        $time = $now->toTimeString();
        $ticketId = DB::table('tickets')->insertGetId
        ([
           'title'                  => $request->title,
           'description'            => $request->description,
           'date'                   => $date,
           'time'                   => $time,
           'sender_user_id'         => Auth::user()->id,
           'unit_id'                => $unitId,
           'receiver_user_id'       => $receiverId
        ]);
        if($ticketId)
        {

            $query = DB::table('ticket_messages')->insert
            ([
               'ticket_id'  => $ticketId,
               'user_id'    => Auth::user()->id,
               'content'    => $request->description,
               'date'       => $date,
               'time'       => $time
            ]);
            if($query)
            {
                return response('اطلاعات شما با موفقیت ثبت گردید');
            }

        }else
            {
                return response('خطایی در ثبت اطلاعات رخ داده است،لطفا با واححد پشتیبانی تماس بگیرید');
            }

    }

    //shiri : below function is to return all tickets to the view to check the status
    public function ticketsManagement($id)
    {
        switch ($id)
        {
            case 1:
                $pageTitle = 'بررسی تیکت های ارسالی';
                $userId = Auth::user()->id;
                $tickets = Ticket::where('sender_user_id' , $userId)->get();
                foreach ($tickets as $ticket)
                {
                $ticket->date = $this->toPersian($ticket->date);
                }
                return view ('user.ticketsManagement',compact('tickets' , 'pageTitle'));
            break;
            case 2:
                $pageTitle = 'بررسی تیکت های دریافتی';
                $unitId = Auth::user()->unit_id;
                $tickets = Ticket::where('unit_id' , $unitId)->get();
                foreach ($tickets as $ticket)
                {
                    $ticket->date = $this->toPersian($ticket->date);
                }
                return view ('user.ticketsManagement',compact('tickets' , 'pageTitle'));

        }

    }



    //below function is to convert to jalali
    public function toPersian($date)
    {
        $gDate = $date;
        if ($date = explode('-', $gDate))
        {
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
        if ($dat2 = explode('/', $date2))
        {
            $year = $dat2[0];
            $month = $dat2[1];
            $day = $dat2[2];
            $gDate2 = $this->jalaliToGregorian($year, $month, $day);
        }
        $gDate2 = $gDate2[0] . '-' . $gDate2[1] . '-' . $gDate2[2];

        switch ($id) {
            case 1 :
                $data = Ticket::whereBetween('date', [$gDate1, $gDate2])->where('sender_user_id', Auth::user()->id)->orderBy('date')->get();
                break;
            case 2 :
                $data = Ticket::whereBetween('date', [$gDate1, $gDate2])->where('unit_id', Auth::user()->unit_id)->orderBy('date')->get();
                break;
        }
        foreach ($data as $date) {
            $date->date = $this->toPersian($date->date);
            $date->unit = $date->user->unit->title;
            $date->user      = $date->user;
        }
       // dd($data);
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
        $tickets = Ticket::where('id',$id)->get();
        $messages = Message::where('ticket_id',$id)->get();
        return view ('user.ticketConversation',compact('messages','tickets','pageTitle'));
    }

    //shiri : below function is to register user message related to ticket
    public function userSendMessage(Request $request)
    {
        $checkTicketToBeActive  = Ticket::where('id',$request->ticketId)->value('active');
        if($checkTicketToBeActive == 0) {
            $userId = Auth::user()->id;
            $now = new Carbon();
            $date = $now->toDateString();
            $time = $now->toTimeString();
            $query = DB::table('ticket_messages')->insertGetId
            ([
                'ticket_id' => $request->ticketId,
                'user_id' => $userId,
                'content' => trim($request->message),
                'time' => $time,
                'date' => $date
            ]);
            if ($query) {
                return response('پیام شما به مسئول مربوطه ارسال گردید');
            } else {
                return response('خطا در ثبت اطلاعات');
            }
        }else
            {
                return response('با توجه به اینکه این تیکت بسته شده است ، امکان ارسال پیام وجود ندارد');
            }
    }


    //shiri : below  function to end ticket by user
    public function endTicket(Request $request)

    {
        $end = Ticket::where('id',$request->ticketId)->update
        ([
            'active'  => 1
        ]);
        if($end)
        {
            return response('تیکت مورد نظر غیر فعال گردید');
        }else
        {
            return response('خطایی رخ داده است ، لطفا با بخش پشتیبانی تماس بگیرید');
        }
    }

    //
    public function dailyWorks()
    {
        $pageTitle = 'امور روزانه کار پرداز';
        $supplierId = Auth::user()->id;
        $requests = Request2::where('supplier_id',$supplierId)->get();
        return view('user.dailyWorks',compact('requests','pageTitle'));
    }

    //
    public function dailyWorksDetails($id)
    {
        $pageTitle = 'مشاهده جزییات امور روزانه';
        $requestRecords = RequestRecord::where([['request_id',$id],['accept',1],['step',8]])->get();
        return view('user.dailyWorksDetails',compact('pageTitle','requestRecords'));
    }
    public function changePassword()
    {
        $pageTitle='تغییر رمز عبور';
        $pageName='changePassword';
        if(Auth::check())
        {
            $userInfo=User::where('id',Auth::user()->id)->get();
            return view('user.changePassword',compact('pageTitle','pageName','userInfo'));
        }
        else
        return redirect('/logout');

    }


    //
    public function saveNewPassword(Request $request)
    {
        if (!$request->ajax())
        {
            abort(403);
        }
        else
        {
            if(Auth::user()->id == $request->userId)
            {

                    if($request->password == $request->confirmPassword)
                    {
                        $q=DB::table('users')->where('id',$request->userId)
                            ->update(['password' => bcrypt($request->password)]);
                        if($q)
                        {
                            //$n=1;
                            return response('رمز عبور شما تغییر یافت');
                        }
                        else
                        {
                            //$n=2;
                            return response('متاسفانه در فرآیند تغییر رمز خطایی رخ داده است!');
                        }
                    }
                    else
                    {
                        return response('رمز و تکرار رمز با یکدیگر یکسان نیست');
                    }

                }
                else
                    {
                        return redirect('/logout');
                    }
        }
    }

}
