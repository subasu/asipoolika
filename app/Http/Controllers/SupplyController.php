<?php

namespace App\Http\Controllers;
//use Illuminate\Http\File;
use App\Http\Requests\AcceptServiceRequestValidation;
use App\Http\Requests\UserCreateValidation;
use App\Models\Message;
use App\Models\Request2;
use App\Models\RequestRecord;
use App\Models\Ticket;
use App\Models\Workers;
use App\User;
use App\Models\Unit;



use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use phpDocumentor\Reflection\Types\Null_;


class SupplyController extends Controller
{
    //

    /* shiri
       below function is to show  all the uncertain service requests to the admin of supply
     */

    public function recentlyAddedService()
    {
        $pageTitle = 'درخواست های خدمت';
        $requests  = Request2::where([['request_type_id',2],['active',0]])->get();
        return view ('admin.recentlyAddedService', compact('pageTitle','requests'));
    }

    public function serviceInProcess()
    {
        $pageTitle ="درخواست های درحال پیگیری";

        $requests=Request2::where('request_type_id',2)->get();
        if(!empty($requests))
        {
            foreach ($requests as $request)
            {
                $request_records=RequestRecord::where([['request_id',$request->id],['active',1],['step',2]])->get();
                $request->records=$request_records;
            }
        }
        return view ('admin.serviceInProcess', compact('pageTitle','requests'));
    }

    /*shiri
        below function is related to  show all request records and those details...
      */
    public function serviceRequestRecords($id)
    {
        $pageTitle = 'جزئیات درخواست شماره : '.$id;
        $records = RequestRecord::where([['request_id',$id],['step',1],['active',0],['refuse_user_id',null]])->get();
        return view ('admin.serviceRequestRecords',compact('pageTitle','records'));
    }

    /* shiri
        below function is related to accept the requested service
    */
    public function acceptProductRequest(Request $request)
    {
        if(!$request->ajax())
        {
            abort(403);
        }
        $id        = $request->id;
        $rate      = $request->rate;
        $price     = $request->price;
        $requestId = $request->requestId;
        $user=Auth::user();
        switch(trim($user->unit->title))
        {
            case 'تدارکات':
                if($user->is_supervisor==1)
                    $step=2;
                else
                    $step=8;
                break;
            case 'انبار':
                $step=3;
                break;
            case 'اعتبار':
                $step=4;
                break;
            case 'امور عمومی':
                $step=5;
                break;
            case 'ریاست':
                $step=6;
                break;
            case 'امور مالی':
                $step=7;
                break;
            default: $step=1;
        }
        if($user->unit->title=='تدارکات' and $user->is_supervisor==1)
        {
            $q=RequestRecord::where('id',$id)->update([
                'rate'=>$rate,
                'price'=>$price,
                'step'=>$step,
                'active'=>1,
                'updated_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
        }
        else
        {
            $q=RequestRecord::where('id',$id)->update([
                'step'=>$step,
                'active'=>1,
                'updated_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
        }

        if($q)
        {
            return response('درخواست ثبت شد به مرحله بعد ارسال شد');
        }
        else
            return response('خطایی رخ داده با پشتیبانی تماس بگیرید');
    }



    /*shiri
      function to refuse request record
    */

    public function refuseRequestRecord(Request $request)
    {
        if(!$request->ajax())
        {
            abort(403);
        }
        $user=Auth::user();
        switch(trim($user->unit->title))
        {
            case 'تدارکات':
                $step=2;
                break;
            case 'انبار':
                $step=3;
                break;
            case 'اعتبار':
                $step=4;
                break;
            case 'امور عمومی':
                $step=5;
                break;
            case 'ریاست':
                $step=6;
                break;
            case 'امور مالی':
                $step=7;
                break;
            default: $step=1;
        }
        $update=
            DB::table('request_records')->where('id',$request->request_record_id)->update([
            'why_not'=>$request->whyNot,
            'step'=> $step,
            'refuse_user_id' => $user->id,
            'updated_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
        ]);
        if($update==1)
        {
            $q=DB::table('requests')->where('id',$request->requestId)->increment('refuse_record_count');
           if($q)
           {
               $class='success';
               return response()->json(['msg'=>'درخواست مربوطه با ثبت دلیل رد شد',
               'class'=>$class]);
           } else
           {
               $class='danger';
               return response()->json(['msg'=>'خطا رخ داده',
                   'class'=>$class]);
           }
        }
       else
       {
           $class='info';
           return response()->json(['msg'=>'آپدیت نشد',
               'class'=>$class]);
       }
    }


    //rayat//show user create form
    public function usersCreateGet()
    {
        $pageTitle='درج کاربر جدید';
        $units = Unit::all();
        return view('admin.usersCreate',compact('units','pageTitle'));
    }

    //rayat//show users's supervisor in user create form by AJAX
    public function usersSupervisor(Request $request)
    {
        $id = $request->id;
        $users_has_supervisor = User::where([['unit_id', $id], ['is_supervisor', 1]])->get();
        if (count($users_has_supervisor)) {
            return response()->json($users_has_supervisor);
        } else {
            $uid = Unit::where('title', 'تدارکات')->value('id');
            $users_has_not_supervisor = User::where([['unit_id', $uid]])->get();
            return response()->json($users_has_not_supervisor);
        }
    }

    //rayat//create user by AJAX
    public function checkUnitSupervisor(UserCreateValidation $request)
    {
       // dd($request->unitManager);
        $unitSupervisor = User::where([['unit_id',$request->unitId],['is_supervisor',1]])->get();
        //dd($unitSupervisor);
        if($unitSupervisor && $request->unitManager === '1' )
        {
            return response('مدیر این واحد قبلا انتخاب شده ، آیا در نظر دارید که ایشان را جایگزین مدیر قبلی نمایید؟');
        }
        elseif(count($unitSupervisor) > 0 && $request->unitManager === null)
            {
                return response('آیا از ثبت کاربر جدید اطمینان دارید؟');
            }
            elseif(count($unitSupervisor) ==  0 && $request->unitManager === null)
            {
                return response('بنابراینکه واحد مربوطه مدیری ندارد ، مدیر تدارکات بعنوان مدیر این واحد در نظر گرفته میشود.آیا تمایل دارید؟');
            }


    }

    public function newUserCreate(UserCreateValidation $request)
    {
        $unitSupervisorId = User::where([['unit_id',$request->unitId],['is_supervisor',1]])->value('id');
        //dd($unitSupervisorId);
        $unitSupervisorUpdate = User::where([['unit_id',$request->unitId],['is_supervisor',1]])->update(['is_supervisor' => 0]);
        $supervisorId = Unit::where('title','تدارکات')->value('supervisor_id');
        if($unitSupervisorUpdate)
        {
            $userId = DB::table('users')->insertGetId
            ([
                'title'             =>$request->title,
                'name'              =>$request->name,
                'family'            =>$request->family,
                'email'             =>$request->email,
                'password'          =>bcrypt($request->password),
                'cellphone'         =>$request->cellphone,
                'internal_phone'    =>$request->internal_phone,
                'description'       =>$request->description,
                'unit_id'           =>$request->unitId,
                'is_supervisor'     => 1,
                'supervisor_id'     =>$supervisorId

            ]);
            if($userId)
            {
                $updateUser  = User::where('supervisor_id',$unitSupervisorId)->update(['supervisor_id' => $userId]);
                $updateUsers = User::where('id',$unitSupervisorId)->update(['supervisor_id' => $userId]);
                if($updateUser && $updateUsers)
                {
                    return response('کاربر جدید درج شد');
                }
            }
        }else
            {
                return response('خطایی رخ داده است ، تماس با بخش پشتیبانی');
            }
    }

    //
    public function newUserWithUnitManager(UserCreateValidation $request)
    {
        $unitSupervisors = User::where([['unit_id',$request->unitId],['is_supervisor',1]])->get();
        $supervisorId   = 0;
        foreach ($unitSupervisors as $unitSupervisor)
        {
            $supervisorId += $unitSupervisor->id;
        }
        //dd($supervisorId);
        $userId = DB::table('users')->insertGetId
        ([
            'title'             =>$request->title,
            'name'              =>$request->name,
            'family'            =>$request->family,
            'email'             =>$request->email,
            'password'          =>bcrypt($request->password),
            'cellphone'         =>$request->cellphone,
            'internal_phone'    =>$request->internal_phone,
            'description'       =>$request->description,
            'unit_id'           =>$request->unitId,
            'supervisor_id'     =>$supervisorId
        ]);
        if($userId)
        {
            return response('کاربر جدید درج شد');
        }
    }
    //
    public function newUserWithoutUnitManager(UserCreateValidation $request)
    {
        $supervisorId = Unit::where('title','تدارکات')->value('supervisor_id');
        $userId = DB::table('users')->insertGetId
        ([
            'title'             =>$request->title,
            'name'              =>$request->name,
            'family'            =>$request->family,
            'email'             =>$request->email,
            'password'          =>bcrypt($request->password),
            'cellphone'         =>$request->cellphone,
            'internal_phone'    =>$request->internal_phone,
            'description'       =>$request->description,
            'unit_id'           =>$request->unitId,
            'supervisor_id'     =>$supervisorId

        ]);
        if($userId)
        {
            return response('کاربر جدید درج شد');
        }
    }

    //rayat//show user create form
    public function unitsCreateGet()
    {
        $pageTitle='درج واحد جدید';
        return view('admin.unitsCreate',compact('pageTitle'));
    }

    //rayat//create user
    public function unitsCreatePost(Request $request)
    {
        $this->validate($request,
            [
                'title' => 'required|max:100',
                'phone' => 'required|numeric',
            ],
            [
                'title.required' => ' فیلد عنوان الزامی است',
                'title.max' => ' فیلد عنوان حداکثر باید 100 کاراکتر باشد ',
                'phone.required' => ' فیلد تلفن الزامی است ',
                'phone.numeric' => ' فیلد تلفن عددی است',
            ]);
        $unit = new Unit();
        $unit->title = $request->title;
        $unit->phone = $request->phone;
        $unit->description = $request->description;
        $unit->organization_id = 1;
        $res = $unit->save();
        return response()->json($res);
    }

    //Rayat//show unit manage
    public function unitsManageGet()
    {
        $pageTitle='مدیریت واحدها';
        $data = Unit::all();
        return view('admin.unitsManage', compact('data','pageTitle'));
    } //Rayat//show user manage

    public function usersManagementGet()
    {
        $pageTitle='مدیریت کاربران';
        $data = User::all();
        //dd($data);
        return view('admin.usersManage', compact('data','pageTitle'));
    }

    //Rayat//active user in usersManage
    public function changeUserStatus(Request $request , $id)
    {
        $userId = $request->userId;
        switch ($id)
        {
            case 1:
                $deactive = User::where('id', $userId)->update(['active' => 0]);
                if($deactive)
                {
                    return response()->json( 'کاربر مورد نظر شما غیر فعال گردید');
                }
                break;
            case 2:
                $active = User::where('id', $userId)->update(['active' => 1]);
                if($active)
                {
                    return response()->json( 'کاربر مورد نظر شما  فعال گردید');
                }
                break;
        }
    }

    //Rayat//active unit in unitsManage
    public function changeUnitStatus(Request $request,$id)
    {
        $unitId = $request->unitId;
        switch ($id)
        {
            case 1:
                $deactive = Unit::where('id', $unitId)->update(['active' => 0]);
                if($deactive)
                {
                    return response()->json( 'واحد مورد نظر شما غیر فعال گردید');
                }
                break;
            case 2:
                $active = Unit::where('id', $unitId)->update(['active' => 1]);
                if($active)
                {
                    return response()->json( 'واحد مورد نظر شما  فعال گردید');
                }
                break;
        }
    }

    //Rayat//show edit unit in unitsManage
    public function unitsUpdateShow($id)
    {
        $pageTitle='ویرایش واحد شماره : '.$id;
        $unit = Unit::where('id', $id)->get();
        return view('admin.unitsUpdate', compact('unit','pageTitle'));
    }

    //Rayat//show edit unit in unitsManage
    public function usersUpdateShow($id)
    {
        $pageTitle = 'ویرایش اطلاعات کاربری';
        $units = Unit::all();
        $user = User::where('id', $id)->get();
        return view('admin.usersUpdate', compact('user', 'units' , 'pageTitle'));
    }

    //Rayat//edit unit in unitsUpdate page by AJAX
    public function unitsUpdate(Request $request)
    {
        $id = $request->unit_id;
        Unit::where('id', $id)->update(['title' => $request->title, 'phone' => $request->phone, 'description' => $request->description]);
        return response()->json();
    }

    //Rayat//edit user in usersUpdate page by AJAX
    public function usersUpdate(Request $request)
    {
        $id = $request->user_id;
        $res=User::where('id','=', $id)
            ->update([
                'title' => $request->title,
                'name' => $request->name,
                'family' => $request->family,
                'email' => $request->email,
                'cellphone' => $request->cellphone,
                'internal_phone' => $request->internal_phone,
                //'unit_id' => $request->unit_id,
                //'supervisor_id' => $request->supervisor_id,
                'description' => $request->description
            ]);
        return response()->json($res);
    }

    /* shiri
       below function is related to show exported workers card
    */
    public function workerCardManage()
    {
        $pageTitle='مدیریت کارت های کارگری';
        $userId = Auth::user()->id;
        $workers = Workers::where([['active',0],['user_id' , $userId]])->orderBy('date')->get();
        foreach ($workers as $worker) {
            $worker->date = $this->toPersian($worker->date);
            $worker->card = 'data:image/jpeg;base64,'.$worker->card;
        }
        return view ('admin.workerCardManage',compact('workers','pageTitle'));
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

    /* shiri
       below function is related to show form of exporting new workers card....
     * */
    public function workerCardCreate()
    {
        $pageTitle="آپلود کارت کارگری";
        return view ('admin.workerCardCreate',compact('pageTitle'));
    }

    /* shiri
       below function is to insert workers card to data base....
    */

    public function addWorkerCard(Request $request)
    {
        //dd('hello');
        if($request->hasFile('image'))
        {
            $jDate = $request->date;
            if ($date = explode('/', $jDate)) {
                $year  = $date[0];
                $month = $date[1];
                $day   = $date[2];
            }
            $gDate = $this->jalaliToGregorian($year, $month, $day);
            $gDate1 = $gDate[0] . '-' . $gDate[1] . '-' . $gDate[2];
            $file = $request->image;
            $file->move(public_path(), $request->image->getClientOriginalName());
            $path = public_path() . '\\' . $request->image->getClientOriginalName();
            //dd($path);
            $image = file_get_contents($path);
            File::delete($path);
            $fileName = base64_encode($image);
            DB::table('workers')->insert(
                [
                    'card'    => $fileName,
                    'user_id' => Auth::user()->id,
                    'date'    => $gDate1,
                    'name'    => $request->name,
                    'family'  => $request->family
                ]
            );
            return response('کارت کارگری مورد نظر شما با موفقیت ثبت گردید');
        }else
            {
                return response('لطفا فایل عکس کارگری خود را انتخاب نمایید ، سپس درخواست خود را وارد نمایید');
            }
    }

    public function jalaliToGregorian($year, $month, $day)
    {
        return Verta::getGregorian($year, $month, $day);
    }


    /*  shiri
        below function is related to search on date. Means that this function get 2 jalali date , first convert them to gregorian date then by
        sql queries serch on date field and then return the result
    */
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
                $data = Workers::whereBetween('date', [$gDate1, $gDate2])->where([['active', 0], ['user_id', $userId]])->orderBy('date')->get();
                break;
        }

        foreach ($data as $date) {
            $date->date = $this->toPersian($date->date);
            $date->card = 'data:image/jpeg;base64,' . $date->card;
        }
        return response()->json(compact('data'));
    }



//pr2
    public function productRequestManagement()
    {
        $pageTitle='مدیریت درخواست کالا تازه ثبت شده';
        $pageName='productRequestManagement';

        $me=Auth::user();

        switch(trim($me->unit->title))
        {
            case 'تدارکات':
                if($me->is_supervisor==1)
                {
                    $step=1;
                    $step2=2;
                }
                    //the user is Karpardaz
                else
                {
                    $step=7;
                    $step2=8;
                }

                break;
            case 'انبار':
                $step=2;
                $step2=3;
                break;
            case 'اعتبار':
                $step=3;
                $step2=4;
                break;
            case 'امور عمومی':
                $step=4;
                $step2=5;
                break;
            case 'ریاست':
                $step=5;
                $step2=6;
                break;
            case 'امور مالی':
                $step=6;
                $step2=7;
                break;
            default: $step=1;$step2=1;
        }
        $requestRecords=RequestRecord::where('step',$step)->pluck('request_id');
//
        $productRequests=Request2::where('request_type_id',3)->whereIn('id',$requestRecords)->get();

        foreach($productRequests as $productRequest)
        {
            //undecided records
            $productRequest->request_record_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step',$step]])->count();
            //in the process records
            $productRequest->request_record_count_accept=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step','>=',$step2],['active',1]])->count();
            //inactive records
            $productRequest->request_record_count_refused=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null]])->count();
        }
//        dd($productRequests);
        return view('admin.productRequestManagement', compact('pageTitle','productRequests','pageName'));
    }
    public function productRequestRecords($id)
    {
        $pageTitle='رکوردهای درخواست شماره '.$id;
        $me=Auth::user();
        $user=Auth::user();
        switch(trim($me->unit->title))
        {
            case 'تدارکات':
                if($me->is_supervisor==1)
                    $step=1;
                //the user is Karpardaz
                else
                    $step=7;
                break;
            case 'انبار':
                $step=2;
                break;
            case 'اعتبار':
                $step=3;
                break;
            case 'امور عمومی':
                $step=4;
                break;
            case 'ریاست':
                $step=5;
                break;
            case 'امور مالی':
                $step=6;
                break;
            default: $step=1;
        }
        $requestRecords=RequestRecord::where([['request_id',$id],['refuse_user_id',null],['step',$step]])->get();
        return view ('admin.productRequestRecords',compact('pageTitle','requestRecords','user'));
    }
    public function serviceRequestManagement()
    {
        $pageTitle='مدیریت درخواست خدمت';
        $pageName='serviceRequestManagement';
        $serviceRequests=Request2::where([['request_type_id',2],['active',0]])->get();
        foreach($serviceRequests as $serviceRequest)
        {
            $serviceRequest->request_record_count=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null]])->count();
            $serviceRequest->request_record_count_refused=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id','!=',null]])->count();
        }
        return view ('admin.serviceRequestManagement', compact('pageTitle','serviceRequests','pageName'));
    }
    public function refusedProductRequestManagementGet()
    {
        $pageTitle='درخواست های رد شده';
        $pageName='refusedProductRequestManagement';
        $user=Auth::user();
        switch(trim($user->unit->title))
        {
            case 'تدارکات':
                $step=2;
                break;
            case 'انبار':
                $step=3;
                break;
            case 'اعتبار':
                $step=4;
                break;
            case 'امور عمومی':
                $step=5;
                break;
            case 'ریاست':
                $step=6;
                break;
            case 'امور مالی':
                $step=7;
                break;
            default: $step=2;$step2=1;
        }
        //uncomment it if every thing goes wrong
//        $requestRecords=RequestRecord::where([['refuse_user_id','!=',null],['active',0]])->pluck('request_id');
        $requestRecords=RequestRecord::where([['refuse_user_id','!=',null],['active',0]])->pluck('request_id');
//        $requestRecords=RequestRecord::where([['refuse_user_id','!=',null],['active',0],['step',3]])->get();
        $productRequests=Request2::where('request_type_id',3)->whereIn('id',$requestRecords)->get();
//        dd($productRequests);
        return view('admin.productRequestManagement', compact('pageTitle','productRequests','pageName'));
    }
    public function acceptProductRequestManagementGet()
    {
        $pageTitle='درخواست های در حال پیگیری';
        $pageName='acceptProductRequestManagement';
        $user=Auth::user();
        switch(trim($user->unit->title))
        {
            case 'تدارکات':
                if($user->is_supervisor==1)
                {
                    $step=2;
                    $step2=1;
                }
                //the user is Karpardaz
                else
                {
                    $step=8;
                    $step2=7;
                }

                break;
            case 'انبار':
                $step=3;
                $step2=2;
                break;
            case 'اعتبار':
                $step=4;
                $step2=3;
                break;
            case 'امور عمومی':
                $step=5;
                $step2=4;
                break;
            case 'ریاست':
                $step=6;
                $step2=5;
                break;
            case 'امور مالی':
                $step=7;
                $step2=6;
                break;
            default: $step=2;$step2=1;
        }
        $requestRecords=RequestRecord::where([['step','>=',$step],['active',1],['refuse_user_id',null]])->pluck('request_id');
        $productRequests=Request2::where('request_type_id',3)->whereIn('id',$requestRecords)->get();
        foreach($productRequests as $productRequest)
        {
            //undecided records
            $productRequest->request_record_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step',$step2]])->count();
            //in the process records
            $productRequest->request_record_count_accept=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step','>=',$step],['active',1]])->count();
            //inactive records
            $productRequest->request_record_count_refused=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null]])->count();
        }
//        dd($productRequests);
        return view ('admin.productRequestManagement', compact('pageTitle','productRequests','pageName'));

    }

    /* shiri
       below function is related to to show workers card image
      */
    public function showWorkerCard($id)
    {
        //dd($id);
        $pageTitle = 'نمایش تصویر کارت کارگری';
        $cards = Workers::where('id',$id)->get();
        foreach ($cards as $card) {
            $card->card = 'data:image/jpeg;base64,'.$card->card;
        }
        //dd($cards);
        return view('admin.showWorkerCard',compact('cards','pageTitle'));
    }

    //shiri : below function is relate to show active ticket to supply admin....
    public function showTickets()
    {
        $pageTitle = 'تیکت های فعال';
      //  $userId = Auth::user()->id;
        $unitId = Auth::user()->unit_id;
        //dd($unitId);
        $tickets = Ticket::where('unit_id' , $unitId)->get();
        foreach ($tickets as $ticket)
        {
            $ticket->date = $this->toPersian($ticket->date);
        }
        return view ('admin.showTickets',compact('pageTitle','tickets'));
    }

    // shiri : below function is to register admin message related to ticket
    public function adminSendMessage(Request $request)
    {
        $message = Message::where('id',$request->messageId)->value('answer');
        //$count = count($message);
        //dd($count);
        if($message == null)
        {
            $query = Message::where('id',$request->messageId)->update
            ([
                'answer' => $request->message,
                'updated_at'=>Carbon::now(new \DateTimeZone('Asia/Tehran'))
            ]);
            if($query)
            {
                return response('پاسخ شما با موفقیت ثبت گردید');
            }
            else
                {
                    return response('خطا در ثبت اطلاعات ، لطفا با بخش پشتیبانی تماس بگیرید');
                }
        }
        else
            {
                return response('قبلا به این پیام پاسخ داده اید،لطفا درخواست مجدد نفرمائید');
            }

    }

    //shiri : below  function to end ticket by admin
    public function adminEndTicket(Request $request)
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

    public function confirmProductRequestManagementGet()
    {
        $pageTitle="مدیریت درخواست های انجام شده";
        $pageName='confirmProductRequest';
        $productRequests=Request2::all();
        foreach($productRequests as $productRequest)
        {
            $all_count=RequestRecord::where('request_id',$productRequest->id)->count();
            $accept_count=RequestRecord::where([['request_id',$productRequest->id],['step',7],['refuse_user_id',null],['active',1]])->count();
            $has_certificate_count=RequestRecord::where([['request_id',$productRequest->id],['step',8]])->count();
            $refuse_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null],['active',0]])->count();
            if($all_count==($accept_count+$refuse_count))
            {
                DB::table('requests')->where('id',$productRequest->id)->update([
                    'active'=>1
                ]);
                $productRequest->msg='Yes';
            }

            else
                $productRequest->msg='No';
            $productRequest->all_count=$all_count;
            $productRequest->accept_count=$accept_count;
            $productRequest->has_certificate_count=$has_certificate_count;
            $productRequest->refuse_count=$refuse_count;
        }
//                dd($productRequests);
        return view('admin.productRequestManagement',compact('pageTitle','productRequests','pageName'));

    }

    //shiri : below function is related to show printed form of product request
    public function printProductRequest($id)
    {
        $pageTitle = 'نسخه چاپی گواهی';
        $productRequestRecords = RequestRecord::where([['request_id',$id],['accept',1]])->get();
        $sum = 0;
        foreach ($productRequestRecords as $productRequestRecord)
        {
            $sum += $productRequestRecord->rate * $productRequestRecord->count;
        }
        return view ('admin.certificate.productRequestForm',compact('pageTitle','productRequestRecords','sum'));
    }
}
