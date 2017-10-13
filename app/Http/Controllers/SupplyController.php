<?php

namespace App\Http\Controllers;
//use Illuminate\Http\File;
use App\Http\Requests\AcceptServiceRequestValidation;
use App\Http\Requests\CostDocumentValidation;
use App\Http\Requests\UserCreateValidation;
use App\Models\Certificate;
use App\Models\CertificateRecord;
use App\Models\CostDocument;
use App\Models\CostDocumentsRecord;
use App\Models\Form;
use App\Models\Message;
use App\Models\Request2;
use App\Models\RequestRecord;
use App\Models\Signature;
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
use function Sodium\increment;


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

    /*shiri
        below function is related to  show all request records and those details...
      */

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
//                if($user->is_supervisor==1)
                $step=2;
//                else
//                    $step=8;
                break;
            case 'انبار':
                $accept=0;
                $step=3;
                break;
            case 'اعتبار':
                $accept=0;
                $step=4;
                break;
            case 'امور عمومی':
                $accept=0;
                $step=5;
                break;
            case 'ریاست':
                $accept=0;
                $step=6;
                break;
            case 'امور مالی':
                $accept=1;
                $step=7;
                break;
            default: $step=1;$accept=0;
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
                'accept'=>$accept,
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
                'active'=>0,
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
        $units = Unit::where('active',1)->get();
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
        //dd(count($unitSupervisor));
        if(count($unitSupervisor) > 0 && $request->unitManager === '1' )
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
        elseif(count($unitSupervisor)== 0 && $request->unitManager === '1')
        {
            return response('آیا از ثبت این کاربر به عنوان مدیر واحد انتخاب شده اطمینان دارید؟');
        }


    }

    public function newUserCreate(UserCreateValidation $request,$id)
    {
        switch ($id)
        {
            case 1:
                $unitSupervisorId = User::where([['unit_id',$request->unitId],['is_supervisor',1]])->value('id');
                //dd($unitSupervisorId);
                $unitSupervisorUpdate = User::where([['unit_id',$request->unitId],['is_supervisor',1]])->update(['is_supervisor' => 0]);
                $supervisorId = Unit::where('title','تدارکات')->value('supervisor_id');
                if($unitSupervisorUpdate)
                {
                    $userId = DB::table('users')->insertGetId
                    ([
                        'title'             =>trim($request->title),
                        'name'              =>trim($request->name),
                        'family'            =>trim($request->family),
                        'email'             =>trim($request->email),
                        'password'          =>bcrypt($request->password),
                        'cellphone'         =>trim($request->cellphone),
                        'internal_phone'    =>trim($request->internal_phone),
                        'description'       =>trim($request->description),
                        'unit_id'           =>$request->unitId,
                        'is_supervisor'     => 1,
                        'supervisor_id'     =>$supervisorId,
                        'created_at'        =>Carbon::now(new \DateTimeZone('Asia/Tehran'))

                    ]);
                    if($userId)
                    {
                        $updateUser  = User::where('supervisor_id',$unitSupervisorId)->update(['supervisor_id' => $userId]);
                        $updateUsers = User::where('id',$unitSupervisorId)->update(['supervisor_id' => $userId]);
                        if($updateUser || $updateUsers)
                        {
                            return response('کاربر جدید درج شد');
                        }
                    }
                }else
                {
                    return response('خطایی رخ داده است ، تماس با بخش پشتیبانی');
                }
                break;

            case 2:
                $unitSupervisors = User::where([['unit_id',$request->unitId],['is_supervisor',1]])->get();
                $supervisorId   = 0;
                foreach ($unitSupervisors as $unitSupervisor)
                {
                    $supervisorId += $unitSupervisor->id;
                }
                //dd($supervisorId);
                $userId = DB::table('users')->insertGetId
                ([
                    'title'             =>trim($request->title),
                    'name'              =>trim($request->name),
                    'family'            =>trim($request->family),
                    'email'             =>trim($request->email),
                    'password'          =>bcrypt($request->password),
                    'cellphone'         =>trim($request->cellphone),
                    'internal_phone'    =>trim($request->internal_phone),
                    'description'       =>trim($request->description),
                    'unit_id'           =>$request->unitId,
                    'supervisor_id'     =>$supervisorId,
                    'created_at'        =>Carbon::now(new \DateTimeZone('Asia/Tehran'))
                ]);
                if($userId)
                {
                    return response('کاربر جدید درج شد');
                }
                break;

            case 3:
                $supervisorId = Unit::where('title','تدارکات')->value('supervisor_id');
                $userId = DB::table('users')->insertGetId
                ([
                    'title'             =>trim($request->title),
                    'name'              =>trim($request->name),
                    'family'            =>trim($request->family),
                    'email'             =>trim($request->email),
                    'password'          =>bcrypt($request->password),
                    'cellphone'         =>trim($request->cellphone),
                    'internal_phone'    =>trim($request->internal_phone),
                    'description'       =>trim($request->description),
                    'unit_id'           =>$request->unitId,
                    'supervisor_id'     =>$supervisorId,
                    'created_at'        =>Carbon::now(new \DateTimeZone('Asia/Tehran'))

                ]);
                if($userId)
                {
                    return response('کاربر جدید درج شد');
                }
                break;

            case 4:
                $supervisorId = Unit::where('title','تدارکات')->value('supervisor_id');
                $userId = DB::table('users')->insertGetId
                ([
                    'title'             =>trim($request->title),
                    'name'              =>trim($request->name),
                    'family'            =>trim($request->family),
                    'email'             =>trim($request->email),
                    'password'          =>bcrypt($request->password),
                    'cellphone'         =>trim($request->cellphone),
                    'internal_phone'    =>trim($request->internal_phone),
                    'description'       =>trim($request->description),
                    'unit_id'           =>$request->unitId,
                    'is_supervisor'     =>1,
                    'supervisor_id'     =>$supervisorId,
                    'created_at'        =>Carbon::now(new \DateTimeZone('Asia/Tehran'))

                ]);
                if($userId)
                {
                    $users = User::where([['is_supervisor',0],['unit_id',$request->unitId],['supervisor_id',$supervisorId]])->get();
                    if(count($users)>0)
                    {
                        $usersWithNewManager = User::where([['is_supervisor',0],['unit_id',$request->unitId],['supervisor_id',$supervisorId]])->update(['supervisor_id' => $userId]);
                        if($usersWithNewManager)
                        {
                            return response('کاربر جدید درج شد');
                        }
                    }
                    return response('کاربر جدید درج شد');

                }
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
        $data = User::where('unit_id','!=',3)->get();
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
            $extension = $request->image->getClientOriginalExtension();
            $fileSize  = $request->image->getClientSize();
            //  dd($fileSize);
            if($fileSize < 150000)
            {
                if($extension == 'png' || $extension == 'PNG')
                {
                    $jDate = $request->date;
                    if ($date = explode('/', $jDate)) {
                        $year = $date[0];
                        $month = $date[1];
                        $day = $date[2];
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
                    $q =  DB::table('workers')->insert
                    ([
                            'card' => $fileName,
                            'user_id' => Auth::user()->id,
                            'date' => $gDate1,
                            'name' => $request->name,
                            'family' => $request->family
                    ]);
                    if($q)
                    {
                        return response('کارت کارگری مورد نظر شما با موفقیت ثبت گردید');
                    }

                }
                else
                    {
                        return response('پسوند فایل امضا باید از نوع png باشد');
                    }
            }else
                {
                    return response('حجم فایل امضا نباید بیش از 1مگابایت باشد');
                }

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
//                if($me->is_supervisor==1)
//                {
                $step=1;
                $step2=2;
//                }
                //the user is Karpardaz
//                else
//                {
//                    $step=7;
//                    $step2=8;
//                }
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
//                if($me->is_supervisor==1)
                $step=1;
                //the user is Karpardaz
//                else
//                    $step=7;
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
//sr2
    public function serviceRequestManagement()
    {
//        $pageTitle='مدیریت درخواست خدمت';
//        $pageName='serviceRequestManagement';
        $pageTitle='مدیریت درخواست خدمت تازه ثبت شده';
        $pageName='productRequestManagement';

        $me=Auth::user();

        switch(trim($me->unit->title))
        {
            case 'تدارکات':
                $step=1;
                $step2=2;
                break;
            case 'اعتبار':
                $step=2;
                $step2=3;
                break;
            case 'امور عمومی':
                $step=3;
                $step2=4;
                break;
            case 'ریاست':
                $step=4;
                $step2=5;
                break;
            case 'امور مالی':
                $step=6;
                $step2=7;
                break;
            default:$step=1;$step2=1;
        }
        $requestRecords=RequestRecord::where('step',$step)->pluck('request_id');
        $serviceRequests=Request2::where('request_type_id',2)->whereIn('id',$requestRecords)->get();
        //درخواست های من بعنوان مسئول واحد
        $service_request_id=Request2::where([['unit_id',$me->unit_id],['request_type_id',2]])->pluck('id');
        $requestRecords2=RequestRecord::where('step',5)->whereIn('request_id',$service_request_id)->pluck('request_id');
        $serviceRequests2=Request2::whereIn('id',$requestRecords2)->get();
        foreach($serviceRequests2 as $serviceRequest)
        {
            //undecided records
            $serviceRequest->request_record_count=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step',5]])->count();
            //in the process records
            $serviceRequest->request_record_count_accept=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step','>=',6],['active',1]])->count();
            //inactive records
            $serviceRequest->request_record_count_refused=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id','!=',null]])->count();
        }


        foreach($serviceRequests as $serviceRequest)
        {
            //undecided records
            $serviceRequest->request_record_count=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step',$step]])->count();
            //in the process records
            $serviceRequest->request_record_count_accept=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step','>=',$step2],['active',1]])->count();
            //inactive records
            $serviceRequest->request_record_count_refused=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id','!=',null]])->count();
        }
        $serviceRequests=$serviceRequests->merge($serviceRequests2);
//        dd($serviceRequests);
        return view('admin.serviceRequestManagement', compact('pageTitle','serviceRequests','pageName'));
    }

    public function serviceRequestRecords($id)
    {
        $pageTitle='رکوردهای درخواست شماره '.$id;
        $me=Auth::user();
        $user=Auth::user();
        switch(trim($me->unit->title))
        {
            case 'تدارکات':
                $step=1;
                break;
            case 'اعتبار':
                $step=2;
                break;
            case 'امور عمومی':
                $step=3;
                break;
            case 'ریاست':
                $step=4;
                break;
            case 'امور مالی':
                $step=6;
                break;
            default: $step=1;
        }
        $requestRecords=RequestRecord::where([['request_id',$id],['refuse_user_id',null],['step',$step]])->get();
        foreach($requestRecords as $requestRecord)
        {
            $requestRecord->mine=0;
        }
        //به عنوان مسئول واحد
        $request_id=Request2::where([['unit_id',$user->unit_id],['id',$id]])->pluck('id');
        $requestRecords2=RequestRecord::whereIn('request_id',$request_id)->where('step',5)->get();
        foreach($requestRecords2 as $requestRecord)
        {
            $requestRecord->mine=1;
        }
        $requestRecords=$requestRecords->merge($requestRecords2);
//dd($requestRecords);
        return view ('admin.serviceRequestRecords',compact('pageTitle','requestRecords','user'));
    }

    public function acceptServiceRequestManagementGet()
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
                else
                {
                    $step=8;
                    $step2=7;
                }
                break;
            case 'اعتبار':
                $step=3;
                $step2=2;
                break;
            case 'امور عمومی':
                $step=4;
                $step2=3;
                break;
            case 'ریاست':
                $step=5;
                $step2=4;
                break;
            case 'امور مالی':
                $step=7;
                $step2=6;
                break;
            default: $step=2;$step2=1;
        }
        $requestRecords=RequestRecord::where([['step','>=',$step],['active',1],['refuse_user_id',null]])->pluck('request_id');
        $serviceRequests=Request2::where('request_type_id',2)->whereIn('id',$requestRecords)->get();
//به عنوان مسئول واحد
        $service_request_id=Request2::where([['unit_id',$user->unit_id],['request_type_id',2]])->pluck('id');
        $requestRecords2=RequestRecord::where([['step','>=',6]],['active',1],['refuse_user_id',null])->whereIn('request_id',$service_request_id)->pluck('request_id');
        $serviceRequests2=Request2::whereIn('id',$requestRecords2)->get();

        foreach($serviceRequests2 as $serviceRequest)
        {
            //undecided records
            $serviceRequest->request_record_count=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step',6]])->count();
            //in the process records
            $serviceRequest->request_record_count_accept=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step','>=',5],['active',1]])->count();
            //inactive records
            $serviceRequest->request_record_count_refused=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id','!=',null]])->count();
        }

        $serviceRequests=$serviceRequests->merge($serviceRequests2);

        foreach($serviceRequests as $productRequest)
        {
            //undecided records
            $productRequest->request_record_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step',$step2]])->count();
            //in the process records
            $productRequest->request_record_count_accept=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step','>=',$step],['active',1]])->count();
            //inactive records
            $productRequest->request_record_count_refused=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null]])->count();
        }
//        dd($serviceRequests);
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

//    public function adminEndTicket(Request $request)
//    {
//        $end = Ticket::where('id',$request->ticketId)->update
//        ([
//           'active'  => 1
//        ]);
//        if($end)
//        {
//            return response('تیکت مورد نظر غیر فعال گردید');
//        }else
//            {
//                return response('خطایی رخ داده است ، لطفا با بخش پشتیبانی تماس بگیرید');
//            }
//    }


    public function confirmProductRequestManagementGet()
    {
        $pageTitle="مدیریت درخواست ها";
        $pageName='confirmProductRequest';
        $productRequests=Request2::where('request_type_id',3)->get();
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

            $certificates=Certificate::where('request_id',$productRequest->id)->get();

            foreach ($certificates as $certificate) {
                $all_c_count=CertificateRecord::where('certificate_id',$certificate->id)->count();
                $finished_c_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',5]])->count();
                if($all_c_count==$finished_c_count)
                {
                    Certificate::where('id',$certificate->id)->update([
                        'active'=>1
                    ]);

                }
            }
        }

        return view('admin.productRequestManagement',compact('pageTitle','productRequests','pageName'));

    }

    //shiri : below function is related to show printed form of product request
    public function printProductRequest($id)
    {
        $formExistence = Request2::where('id',$id)->value('request_form_id');
        if($formExistence != 0)
        {
            $formContents = Form::where('id',$formExistence)->get();
            $pageTitle = 'نسخه چاپی گواهی';
            return view('admin.certificate.productRequestForm',compact('formContents','pageTitle'));
        }
        else {
            $storageId = Unit::where('title', 'انبار')->value('id');
            $storageSupervisorInfo = User::where([['unit_id', $storageId], ['is_supervisor', 1]])->get();
            $storageSupervisorId = 0;
            $storageSupervisorName = '';
            $storageSupervisorFamily = '';
            foreach ($storageSupervisorInfo as $storageSupervisorInf) {
                $storageSupervisorId += $storageSupervisorInf->id;
                $storageSupervisorName .= $storageSupervisorInf->name;
                $storageSupervisorFamily .= $storageSupervisorInf->family;
            }
            $storageSupervisorFullName = $storageSupervisorName . chr(10) . $storageSupervisorFamily;
            $storageSupervisorSignature = Signature::where('user_id', $storageSupervisorId)->value('signature');
            $storageSupervisorSignature = 'data:image/png;base64,' . $storageSupervisorSignature;


            $originalJobId = Unit::where('title', 'امور عمومی')->value('id');
            $originalJobSupervisorInfo = User::where([['unit_id', $originalJobId], ['is_supervisor', 1]])->get();
            $originalJobSupervisorId = 0;
            $originalJobSupervisorName = '';
            $originalJobSupervisorFamily = '';
            foreach ($originalJobSupervisorInfo as $originalJobSupervisorInf) {
                $originalJobSupervisorId += $originalJobSupervisorInf->id;
                $originalJobSupervisorName .= $originalJobSupervisorInf->name;
                $originalJobSupervisorFamily .= $originalJobSupervisorInf->family;
            }
            $originalJobSupervisorFullName = $originalJobSupervisorName . chr(10) . $originalJobSupervisorFamily;
            $originalJobSupervisorSignature = Signature::where('user_id', $originalJobSupervisorId)->value('signature');
            $originalJobSupervisorSignature = 'data:image/png;base64,' . $originalJobSupervisorSignature;

            $bossUnitId = Unit::where('title', 'ریاست')->value('id');
            $bossInfo = User::where([['unit_id', $bossUnitId], ['is_supervisor', 1]])->get();
            $bossId = 0;
            $bossName = '';
            $bossFamily = '';
            foreach ($bossInfo as $bossInf) {
                $bossId += $bossInf->id;
                $bossName .= $bossInf->name;
                $bossFamily .= $bossInf->family;
            }
            $bossFullName = $bossName . chr(10) . $bossFamily;
            $bossSignature = Signature::where('user_id', $bossId)->value('signature');
            $bossSignature = 'data:image/png;base64,' . $bossSignature;


            $creditUnitId = Unit::where('title', 'اعتبار')->value('id');
            $creditSupervisorInfo = User::where([['unit_id', $creditUnitId], ['is_supervisor', 1]])->get();
            $creditSupervisorId = 0;
            $creditSupervisorName = '';
            $creditSupervisorFamily = '';
            foreach ($creditSupervisorInfo as $creditSupervisorInf) {
                $creditSupervisorId += $creditSupervisorInf->id;
                $creditSupervisorName .= $creditSupervisorInf->name;
                $creditSupervisorFamily .= $creditSupervisorInf->family;
            }
            $creditSupervisorFullName = $creditSupervisorName . chr(10) . $creditSupervisorFamily;
            $creditSupervisorSignature = Signature::where('user_id', $creditSupervisorId)->value('signature');
            $creditSupervisorSignature = 'data:image/png;base64,' . $creditSupervisorSignature;

            $financeUnitId = Unit::where('title', 'امور مالی')->value('id');
            $financeSupervisorInfo = User::where([['unit_id', $financeUnitId], ['is_supervisor', 1]])->get();
            $financeSupervisorId = 0;
            $financeSupervisorName = '';
            $financeSupervisorFamily = '';
            foreach ($financeSupervisorInfo as $financeSupervisorInf) {
                $financeSupervisorId = $financeSupervisorInf->id;
                $financeSupervisorName = $financeSupervisorInf->name;
                $financeSupervisorFamily = $financeSupervisorInf->family;
            }
            $financeSupervisorFullName = $financeSupervisorName . chr(10) . $financeSupervisorFamily;
            $financeSupervisorSignature = Signature::where('user_id', $financeSupervisorId)->value('signature');
            $financeSupervisorSignature = 'data:image/png;base64,' . $financeSupervisorSignature;

            $pageTitle = 'نسخه چاپی گواهی';
            $productRequestRecords = RequestRecord::where([['request_id', $id], ['accept', 1]])->get();
            $sum = 0;
            foreach ($productRequestRecords as $productRequestRecord) {
                $sum += $productRequestRecord->rate * $productRequestRecord->count;
            }
            return view('admin.certificate.productRequestForm', compact('pageTitle', 'productRequestRecords', 'sum', 'storageSupervisorSignature', 'originalJobSupervisorSignature', 'bossSignature', 'creditSupervisorSignature', 'financeSupervisorSignature', 'storageSupervisorFullName', 'originalJobSupervisorFullName', 'bossFullName', 'creditSupervisorFullName', 'financeSupervisorFullName'));
        }
    }

    //shiri : below function is related to print service_request_form
    public function printServiceRequest($id)
    {
        $formExistence = Request2::where('id',$id)->value('request_form_id');
        if($formExistence != 0)
        {
            $formContents = Form::where('id',$formExistence)->get();
            $pageTitle = 'نسخه چاپی گواهی';
            return view('admin.certificate.serviceRequestForm',compact('formContents','pageTitle'));
        }
        else
            {
            $supplyId = Unit::where('title', 'تدارکات')->value('id');
            $supplySupervisorInfo = User::where([['unit_id', $supplyId], ['is_supervisor', 1]])->get();
            $supplySupervisorId = 0;
            $supplySupervisorName = '';
            $supplySupervisorFamily = '';
            foreach ($supplySupervisorInfo as $supplySupervisorInf) {
                $supplySupervisorId += $supplySupervisorInf->id;
                $supplySupervisorName .= $supplySupervisorInf->name;
                $supplySupervisorFamily .= $supplySupervisorInf->family;
            }
            $supplySupervisorSignature = Signature::where('user_id', $supplySupervisorId)->value('signature');
            $supplySupervisorSignature = 'data:image/png;base64,' . $supplySupervisorSignature;
            $supplySupervisorFullName = $supplySupervisorName . chr(10) . $supplySupervisorFamily;


            $bossUnitId = Unit::where('title', 'ریاست')->value('id');
            $bossInfo = User::where([['unit_id', $bossUnitId], ['is_supervisor', 1]])->get();
            $bossId = 0;
            $bossName = '';
            $bossFamily = '';
            foreach ($bossInfo as $bossInf) {
                $bossId += $bossInf->id;
                $bossName .= $bossInf->name;
                $bossFamily .= $bossInf->family;
            }
            $bossFullName = $bossName . chr(10) . $bossFamily;
            $bossSignature = Signature::where('user_id', $bossId)->value('signature');
            $bossSignature = 'data:image/png;base64,' . $bossSignature;

            $creditUnitId = Unit::where('title', 'اعتبار')->value('id');
            $creditSupervisorInfo = User::where([['unit_id', $creditUnitId], ['is_supervisor', 1]])->get();
            $creditSupervisorId = 0;
            $creditSupervisorName = '';
            $creditSupervisorFamily = '';
            foreach ($creditSupervisorInfo as $creditSupervisorInf) {
                $creditSupervisorId += $creditSupervisorInf->id;
                $creditSupervisorName .= $creditSupervisorInf->name;
                $creditSupervisorFamily .= $creditSupervisorInf->family;
            }
            $creditSupervisorFullName = $creditSupervisorName . chr(10) . $creditSupervisorFamily;
            $creditSupervisorSignature = Signature::where('user_id', $creditSupervisorId)->value('signature');
            $creditSupervisorSignature = 'data:image/png;base64,' . $creditSupervisorSignature;

            $financeUnitId = Unit::where('title', 'امور مالی')->value('id');
            $financeSupervisorInfo = User::where([['unit_id', $financeUnitId], ['is_supervisor', 1]])->get();
            $financeSupervisorId = 0;
            $financeSupervisorName = '';
            $financeSupervisorFamily = '';
            foreach ($financeSupervisorInfo as $financeSupervisorInf) {
                $financeSupervisorId = $financeSupervisorInf->id;
                $financeSupervisorName = $financeSupervisorInf->name;
                $financeSupervisorFamily = $financeSupervisorInf->family;
            }
            $financeSupervisorFullName = $financeSupervisorName . chr(10) . $financeSupervisorFamily;
            $financeSupervisorSignature = Signature::where('user_id', $financeSupervisorId)->value('signature');
            $financeSupervisorSignature = 'data:image/png;base64,' . $financeSupervisorSignature;

            $pageTitle = 'نسخه چاپی گواهی';
            $productRequestRecords = RequestRecord::where([['request_id', $id], ['accept', 1]])->get();
            $sum = 0;
            $unitName = '';
            $unitId = 0;
            $requestNumber = 0;
            $date = '';
            foreach ($productRequestRecords as $productRequestRecord) {
                $sum += $productRequestRecord->rate * $productRequestRecord->count;
                if ($unitName == '' && $requestNumber == 0 && $date == '' && $unitId == 0) {
                    $unitName .= $productRequestRecord->request->unit->title;
                    $requestNumber += $productRequestRecord->id;
                    $date .= $this->toPersian($productRequestRecord->request->created_at->toDateString());
                    $unitId += $productRequestRecord->request->unit_id;

                }
            }
            $unitSupervisorInfo = User::where([['unit_id', $unitId], ['is_supervisor', 1]])->get();
            $unitSupervisorId = 0;
            $unitSupervisorName = '';
            $unitSupervisorFamily = '';
            foreach ($unitSupervisorInfo as $unitSupervisorInf) {
                $unitSupervisorId += $unitSupervisorInf->id;
                $unitSupervisorName .= $unitSupervisorInf->name;
                $unitSupervisorFamily .= $unitSupervisorInf->family;
            }
            $unitSupervisorFullName = $unitSupervisorName . chr(10) . $unitSupervisorFamily;
            $unitSupervisorSignature = Signature::where('user_id', $unitSupervisorId)->value('signature');
            $unitSupervisorSignature = 'data:image/png;base64,' . $unitSupervisorSignature;
            return view('admin.certificate.serviceRequestForm', compact('productRequestRecords', 'pageTitle', 'sum', 'unitName', 'requestNumber', 'date', 'supplySupervisorSignature', 'originalJobSupervisorSignature', 'bossSignature', 'creditSupervisorSignature', 'financeSupervisorSignature', 'creditSupervisorFullName', 'financeSupervisorFullName', 'bossFullName', 'supplySupervisorFullName', 'unitSupervisorName', 'unitSupervisorFullName', 'unitSupervisorSignature'));
        }
    }

    //shiri : below function is related to export delivery and install certificate
    public function exportDeliveryInstallCertificate($id)
    {
        $pageTitleInstall = 'صدور گواهی تحویل و نصب';
        $pageTitleUse     = 'صدور گواهی تحویل و نصب';
        $oldCertificates = Form::where('certificate_id',$id)->get();
        if(count($oldCertificates) > 0)
        {
            return view('admin.certificate.exportDeliveryInstallCertificate',compact('pageTitleInstall','oldCertificates','pageTitleUse'));
        }
        else
            {
                $bossUnitId = Unit::where('title','ریاست')->value('id');
                $bossInfo = User::where([['unit_id',$bossUnitId],['is_supervisor',1]])->get();
                $bossId = 0;
                $bossName = '';
                $bossFamily = '';
                foreach ($bossInfo as $bossInf)
                {
                    $bossId += $bossInf->id;
                    $bossName .= $bossInf->name;
                    $bossFamily .= $bossInf->family;
                }
                $bossFullName = $bossName .chr(10).$bossFamily;
                $bossSignature = Signature::where('user_id',$bossId)->value('signature');
                $bossSignature = 'data:image/png;base64,'.$bossSignature;


                $certificates = Certificate::where('id',$id)->get();
                $shopComp  = '';
                $requestId = 0;
                $date      = '';
                $certificateId = 0;
                foreach ($certificates as $certificate)
                {
                    $shopComp       .= $certificate->shop_comp;
                    $requestId      += $certificate->request_id;
                    $date           .= $this->toPersian($certificate->created_at->toDateString());
                    $certificateId  += $certificate->id;
                }
                //$certificateId        = Certificate::where('request_id',$id)->pluck('id');
                $unitId               = Request2::where('id',$requestId)->value('unit_id');
                $certificateRecords = CertificateRecord::where('certificate_id',$id)->get();
                $unitName = Unit::where('id',$unitId)->value('title');
                $sum = 0;
                $receiverId = 0;
                $receiverName   = '';
                $receiverFamily = '';
                $supplierId     = 0;
                foreach ($certificateRecords as $certificateRecord)
                {
                    $sum += $certificateRecord->count * $certificateRecord->rate;
                    if($receiverName == '' && $receiverFamily == '')
                    {
                        $receiverId     += $certificateRecord->user->id;
                        $receiverName   .= $certificateRecord->user->name;
                        $receiverFamily .= $certificateRecord->user->family;
                        $supplierId     += $certificateRecord->certificate->request->supplier_id;
                    }

                }

                $receiverSignature = Signature::where('user_id',$receiverId)->value('signature');
                $receiverSignature = 'data:image/png;base64,'.$receiverSignature;
                $receiverFullName = $receiverName .chr(10).$receiverFamily;


                $supplierName = User::where('id',$supplierId)->value('name');
                $supplierFamily = User::where('id',$supplierId)->value('family');
                $supplierFullName = $supplierName .chr(10).$supplierFamily;
                $supplierSignature = Signature::where('user_id',$supplierId)->value('signature');
                $supplierSignature = 'data:image/png;base64,'.$supplierSignature;

                $unitSupervisorInfo = User::where([['unit_id',$unitId],['is_supervisor',1]])->get();
                $unitSupervisorId = 0;
                $unitSupervisorName = '';
                $unitSupervisorFamily = '';
                foreach ($unitSupervisorInfo as $unitSupervisorInf)
                {
                    $unitSupervisorId     +=$unitSupervisorInf->id;
                    $unitSupervisorName   .= $unitSupervisorInf->name;
                    $unitSupervisorFamily .= $unitSupervisorInf->family;
                }
                $unitSupervisorFullName = $unitSupervisorName .chr(10).$unitSupervisorFamily;
                $unitSupervisorSignature = Signature::where('user_id',$unitSupervisorId)->value('signature');
                $unitSupervisorSignature = 'data:image/png;base64,'.$unitSupervisorSignature;
                return view('admin.certificate.exportDeliveryInstallCertificate',compact('unitSupervisorSignature','supplierFullName','supplierSignature','unitSupervisorFullName','receiverSignature','receiverFullName','bossSignature','bossFullName','pageTitleInstall','pageTitleUse','certificateRecords' , 'sum','unitSupervisorName','unitSupervisorFamily','shopComp','unitName','receiverName','receiverFamily','certificateId','date'));
            }
    }


    //shiri : below function is related to save forms
   public function formSave(Request $request,$id)
    {

        $userId = Auth::user()->id;
        switch ($id) {
            case 1:
                if($request->formId)
                {
                    $checkFormExistence = Form::where('id',$request->formId)->get();
                    $countFormExistence  = count($checkFormExistence);
                    if($countFormExistence > 0)
                    {
                        $count = Form::where('id', $request->formId)->increment('print_count');
                        $formPrintId = DB::table('print_form')->insertGetId
                        ([

                            'form_id'    => $request->formId,
                            'printed_by' => $userId,
                        ]);
                        if ($count && $formPrintId) {
                            return response('اطلاعات فرم شما ذخیره گردید');
                        }
                    }else
                    {
                        return response('در ثبت اطلاعات مشکلی رخ داده است.لطفا با بخش پشتیبانی تماس بگیرید');
                    }

                }
                else {
                    $formId = DB::table('forms')->insertGetId
                    ([
                        'title'      => 'خلاصه تنظیمی',
                        'request_id' => $request->requestId,
                        'content' => $request->body,

                    ]);
                    if ($formId) {
                        $count = Form::where('id', $formId)->increment('print_count');
                        $formPrintId = DB::table('print_form')->insertGetId
                        ([
                            'form_id' => $formId,
                            'printed_by' => $userId,
                        ]);

                    }
                    $updateRequestFactorFormId = Request2::where('id', $request->requestId)->update(['factor_form_id' => $formId]);
                    if ($count && $formPrintId && $updateRequestFactorFormId) {
                        return response('اطلاعات فرم شما ذخیره گردید');
                    }
                }
                break;

            case 2:
                if($request->formId)
                {
                    $checkFormExistence = Form::where('id',$request->formId)->get();
                    $countFormExistence  = count($checkFormExistence);
                    if($countFormExistence > 0)
                    {
                        $count = Form::where('id', $request->formId)->increment('print_count');
                        $formPrintId = DB::table('print_form')->insertGetId
                        ([

                            'form_id'    => $request->formId,
                            'printed_by' => $userId,
                        ]);
                        if ($count && $formPrintId) {
                            return response('اطلاعات فرم شما ذخیره گردید');
                        }
                    }else
                    {
                        return response('در ثبت اطلاعات مشکلی رخ داده است.لطفا با بخش پشتیبانی تماس بگیرید');
                    }

                }
                else {
                    $formId = DB::table('forms')->insertGetId
                    ([
                        'title'      => 'فرم درخواست',
                        'request_id' => $request->requestId,
                        'content' => $request->body,

                    ]);
                    if ($formId) {
                        $count = Form::where('id', $formId)->increment('print_count');
                        $formPrintId = DB::table('print_form')->insertGetId
                        ([
                            'form_id' => $formId,
                            'printed_by' => $userId,
                        ]);

                    }
                    $updateRequestFactorFormId = Request2::where('id', $request->requestId)->update(['request_form_id' => $formId]);
                    if ($count && $formPrintId && $updateRequestFactorFormId) {
                        return response('اطلاعات فرم شما ذخیره گردید');
                    }
                }
                break;
            case 3:
                $formId = Form::where('certificate_id', $request->certificateId)->value('id');
                if ($formId > 0) {
                    $count = Form::where('certificate_id', $request->certificateId)->increment('print_count');
                    $formPrintId = DB::table('print_form')->insertGetId
                    ([
                        'form_id' => $formId,
                        'printed_by' => $userId,
                    ]);
                    if ($count && $formPrintId) {
                        return response('اطلاعات فرم شما ذخیره گردید');

                    }
                } else {
                    $formId = DB::table('forms')->insertGetId
                    ([
                        'request_id' => $request->requestId,
                        'content' => $request->body,
                        'title' => $request->title,
                        'certificate_id' => $request->certificateId
                    ]);
                    if ($formId) {
                        $count = Form::where('id', $formId)->increment('print_count');
                        $formPrintId = DB::table('print_form')->insertGetId
                        ([
                            'form_id' => $formId,
                            'printed_by' => $userId,
                        ]);
                        if ($count && $formPrintId) {
                            return response('اطلاعات فرم شما ذخیره گردید');
                        }

                    }
                }

                break;
            case 4:
                $formId = Form::where('certificate_id', $request->certificateId)->value('id');
                if (count($formId) > 0) {
                    $count = Form::where('certificate_id', $request->certificateId)->increment('print_count');
                    $formPrintId = DB::table('print_form')->insertGetId
                    ([
                        'form_id' => $formId,
                        'printed_by' => $userId,
                    ]);
                    if ($count && $formPrintId) {
                        return response('اطلاعات فرم شما ذخیره گردید');
                    }
                } else {
                    $formId = DB::table('forms')->insertGetId
                    ([
                        'request_id' => $request->requestId,
                        'content' => $request->body,
                        'title' => 'گواهی تحویل خدمت',
                        'certificate_id' => $request->certificateId
                    ]);
                    if ($formId) {
                        $count = Form::where('id', $formId)->increment('print_count');
                        $formPrintId = DB::table('print_form')->insertGetId
                        ([
                            'form_id' => $formId,
                            'printed_by' => $userId,
                        ]);
                        if ($count && $formPrintId) {
                            return response('اطلاعات فرم شما ذخیره گردید');
                        }

                    }
                }
                break;
            case 5:
                $printCount = CostDocument::where('id',$request->costDocumentId)->increment('print_count',1);
                if($printCount > 0)
                {
                    $formPrint = DB::table('print_form')->insert
                    ([
                        'cost_document_id' => $request->costDocumentId,
                        'printed_by'       => $userId
                    ]);
                    if($formPrint)
                    {
                        return response('لطفا برای چاپ فرم کلیک نمایید');
                    }
                }

                break;

        }
    }
    public function confirmServiceRequestManagementGet()
    {
        $pageTitle="مدیریت درخواست های تایید شده";
        $pageName='confirmProductRequest';
        $productRequests=Request2::where('request_type_id',2)->get();

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

            $certificates=Certificate::where('request_id',$productRequest->id)->get();

            foreach ($certificates as $certificate) {
                $all_c_count=CertificateRecord::where('certificate_id',$certificate->id)->count();
                $finished_c_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',5]])->count();
                if($all_c_count==$finished_c_count)
                {
                    Certificate::where('id',$certificate->id)->update([
                        'active'=>1
                    ]);

                }
            }
        }
//        dd($productRequests);
        return view('admin.productRequestManagement',compact('pageTitle','productRequests','pageName'));
    }

    public function acceptServiceRequest(Request $request)
    {
        if(!$request->ajax())
        {
            abort(403);
        }
        $id        = $request->id;
        $rate      = $request->rate;
        $price     = $request->price;
        $requestId = $request->requestId;
        $mine=$request->mine;
        $user=Auth::user();
        switch(trim($user->unit->title))
        {
            case 'تدارکات':
                $step=2;
                break;
            case 'اعتبار':
                $accept=0;
                $step=3;
                break;
            case 'امور عمومی':
                $accept=0;
                $step=4;
                break;
            case 'ریاست':
                $request=Request2::where('id',$requestId)->get();
                if($request[0]->unit->title=='تدارکات')
                {
                    $accept=0;
                    $step=6;
                }
                else{
                    $accept=0;
                    $step=5;
                }
                break;
            case 'امور مالی':
                $accept=1;
                $step=7;
                break;
            default: $step=1;$accept=0;
        }
        //این درخواست ثبت شده توسط واحد منه
        if($mine==1)
        {
            $step_record=RequestRecord::where('id',$id)->pluck('step');
            if($step_record[0]==5)
                $step=6;
            else $step=$step_record[0]++;
        }
        //این درخواست توسط واحد من درج نشده است

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
                'accept'=>$accept,
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

    //shiri : below function is related to show certificates
    public function showCertificates($id)
    {
        $pageTitle    = 'لیست گواهی ها';
        $certificates = Certificate::where('request_id',$id)->get();
        return view('admin.certificate.showCertificates',compact('certificates','pageTitle'));
    }
   
  //
    public function printServiceDeliveryForm($id)
    {
        //dd($id);
        $formExistence = Form::where('certificate_id',$id)->get();
        if(count($formExistence) > 0)
        {
          //  dd($formExistence);
            $pageTitle = 'صدور گواهی تحویل و نصب';
            return view('admin.certificate.serviceDeliveryForm',compact('formExistence','pageTitle'));
        }
        else {

            $supplyId = Unit::where('title', 'تدارکات')->value('id');
            $supplySupervisorInfo = User::where([['unit_id', $supplyId], ['is_supervisor', 1]])->get();
            $supplySupervisorId = 0;
            $supplySupervisorName = '';
            $supplySupervisorFamily = '';
            foreach ($supplySupervisorInfo as $supplySupervisorInf) {
                $supplySupervisorId += $supplySupervisorInf->id;
                $supplySupervisorName .= $supplySupervisorInf->name;
                $supplySupervisorFamily .= $supplySupervisorInf->family;
            }
            $supplySupervisorSignature = Signature::where('user_id', $supplySupervisorId)->value('signature');
            $supplySupervisorSignature = 'data:image/png;base64,' . $supplySupervisorSignature;
            $supplySupervisorFullName = $supplySupervisorName . chr(10) . $supplySupervisorFamily;

            $bossUnitId = Unit::where('title', 'ریاست')->value('id');
            $bossInfo = User::where([['unit_id', $bossUnitId], ['is_supervisor', 1]])->get();
            $bossId = 0;
            $bossName = '';
            $bossFamily = '';
            foreach ($bossInfo as $bossInf) {
                $bossId += $bossInf->id;
                $bossName .= $bossInf->name;
                $bossFamily .= $bossInf->family;
            }
            $bossFullName = $bossName . chr(10) . $bossFamily;
            $bossSignature = Signature::where('user_id', $bossId)->value('signature');
            $bossSignature = 'data:image/png;base64,' . $bossSignature;


            $certificates = Certificate::where('id', $id)->get();
            $shopComp = '';
            $requestId = 0;

            foreach ($certificates as $certificate) {
                $shopComp .= $certificate->shop_comp;
                $requestId += $certificate->request_id;

            }
            $pageTitle = 'صدور گواهی تحویل و نصب';
            $unitId = Request2::where('id', $requestId)->value('unit_id');
            $certificateRecords = CertificateRecord::where('certificate_id', $id)->get();
            $unitName = Unit::where('id', $unitId)->value('title');
            $sum = 0;
            $receiverId = 0;
            $receiverName = '';
            $receiverFamily = '';
            $requestId = 0;
            foreach ($certificateRecords as $certificateRecord) {
                $sum += $certificateRecord->count * $certificateRecord->rate;
                if ($receiverName == '' && $receiverFamily == '') {
                    $receiverId += $certificateRecord->user->id;
                    $receiverName .= $certificateRecord->user->name;
                    $receiverFamily .= $certificateRecord->user->family;
                    $requestId += $certificateRecord->certificate->request_id;
                }

            }
            $receiverSignature = Signature::where('user_id', $receiverId)->value('signature');
            $receiverSignature = 'data:image/png;base64,' . $receiverSignature;
            $receiverFullName = $receiverName . chr(10) . $receiverFamily;

            $unitSupervisorInfo = User::where([['unit_id', $unitId], ['is_supervisor', 1]])->get();
            $unitSupervisorId = 0;
            $unitSupervisorName = '';
            $unitSupervisorFamily = '';
            foreach ($unitSupervisorInfo as $unitSupervisorInf) {
                $unitSupervisorId += $unitSupervisorInf->id;
                $unitSupervisorName .= $unitSupervisorInf->name;
                $unitSupervisorFamily .= $unitSupervisorInf->family;
            }
            $unitSupervisorFullName = $unitSupervisorName . chr(10) . $unitSupervisorFamily;
            $unitSupervisorSignature = Signature::where('user_id', $unitSupervisorId)->value('signature');
            $unitSupervisorSignature = 'data:image/png;base64,' . $unitSupervisorSignature;
            return view('admin.certificate.serviceDeliveryForm', compact('supplySupervisorFullName', 'bossFullName', 'bossSignature', 'supplySupervisorSignature', 'unitSupervisorFullName', 'unitSupervisorSignature', 'certificateRecords', 'shopComp', 'requestId', 'pageTitle', 'unitName', 'receiverFullName', 'sum', 'receiverSignature', 'requestId'));
        }
    }
  
    //shiri : below function is related to print summary of requests
    public function printFactors($id)
    {
        $formExistence = Request2::where('id',$id)->value('factor_form_id');
        if($formExistence != 0)
        {
            $formContents = Form::where('id',$formExistence)->get();
            $pageTitle = 'چاپ خلاصه تنظیمی';
            return view('admin.certificate.factorsForm',compact('formContents','pageTitle'));
        }
        else
            {
                $pageTitle = 'چاپ خلاصه تنظیمی';
                $productRequestRecords = RequestRecord::where([['request_id',$id],['accept',1]])->get();
                $sum = 0;
                $supplierId = 0;
                foreach ($productRequestRecords as $productRequestRecord)
                {
                    $sum += $productRequestRecord->count * $productRequestRecord->rate;
                    if($supplierId == 0)
                    {
                        $supplierId += $productRequestRecord->request->supplier_id;
                    }

                }
                $supplierName = User::where('id',$supplierId)->value('name');
                $supplierFamily = User::where('id',$supplierId)->value('family');
                $supplierFullName = $supplierName .chr(10).$supplierFamily;
                return view ('admin.certificate.factorsForm',compact('pageTitle','productRequestRecords','sum','supplierFullName'));
            }

    }

    //shiri:
    public function costDocumentForm($id)
    {
        $pageTitle = 'سند هزینه';
        $oldCostDocumentsId = CostDocument::where('request_id',$id)->value('id');

        if($oldCostDocumentsId > 0)
        {
            $sumGeneralPrice = 0;
            $sumDeduction    = 0;
            $sumPayedPrice   = 0;
            $costDocumentsRecords = CostDocumentsRecord::where('cost_document_id',$oldCostDocumentsId)->get();
            foreach ($costDocumentsRecords as $costDocumentsRecord)
            {
                $sumGeneralPrice += $costDocumentsRecord->general_price;
                $sumDeduction    += $costDocumentsRecord->deduction;
                $sumPayedPrice   += $costDocumentsRecord->payed_price;
            }
            return view('admin.certificate.costDocumentForm',compact('costDocumentsRecords','pageTitle','sumDeduction','sumPayedPrice','sumGeneralPrice'));
        }else
            {
                return view('admin.certificate.costDocumentForm',compact('id','pageTitle'));
            }

    }

    //
    public function saveCostDocument(CostDocumentValidation $request)
    {
        $oldCostDocument = CostDocument::where('request_id',$request->requestId)->get();
        if(count($oldCostDocument) > 0)
        {
            return response('سند هزینه این درخواست قبلا ثبت ثبت گردیده است');
        }else
            {
                $recordCount = $request->recordCount;
                if($recordCount == "")
                {
                    return response('محتویات سند خالی است ، لطفا سند را پر نمایید سپس درخواست مجدد بدهید.');
                }
                else
                    {
                        $costDocumentId = DB::table('cost_documents')->insertGetId
                        ([
                               'request_id'  => $request->requestId,
                             //  'content'     => $request->bodyContent,
                               'created_at'  => Carbon::now(new \DateTimeZone('Asia/Tehran'))
                        ]);
                        if($costDocumentId)
                        {
                            $i = 0;
                            while( $i < $recordCount )
                            {
                                $costDocumentRecords = DB::table('cost_document_records')->insert
                                ([
                                    'cost_document_id' => $costDocumentId,
                                    'code'             => trim($request->code[$i]),
                                    'description'      => trim($request->description[$i]),
                                    'moein_office'     => trim($request->moeinOffice[$i]),
                                    'general_price'    => trim($request->generalPrice[$i]),
                                    'deduction'        => trim($request->deduction[$i]),
                                    'payed_price'      => trim($request->payedPrice[$i]),
                                    'page'             => trim($request->page[$i]),
                                    'row'              => trim($request->row[$i]),
                                    'created_at'       => Carbon::now(new \DateTimeZone('Asia/Tehran'))

                                ]);
                                $i++;

                            }
                        }

                        if($costDocumentRecords)
                        {
                            return response('اطلاعات با موفقیت ثبت شد');
                        }
                    }

            }


    }


}
