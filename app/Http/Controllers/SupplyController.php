<?php

namespace App\Http\Controllers;
//use Illuminate\Http\File;
use App\Http\Requests\AcceptServiceRequestValidation;
use App\Http\Requests\CostDocumentValidation;
use App\Http\Requests\SaveBillValidation;
use App\Http\Requests\UserCreateValidation;
use App\Models\Bill;
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
use App\Models\Warehouse;
use App\Models\Workers;
use App\User;
use App\Models\Unit;



use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
        $rate      = encrypt($request->rate);
        $price     = encrypt($request->price);
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
                'why_not'=>encrypt($request->whyNot),
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
       // dd($request->unitId);
        switch ($id)
        {
            case 1:
                $unitSupervisorId = User::where([['unit_id',$request->unitId],['is_supervisor',1]])->value('id');
                //dd($unitSupervisorId);
                $unitSupervisorUpdate = User::where('id',$unitSupervisorId)->update(['is_supervisor' => 0]);
                $supervisorId = Unit::where('title','تدارکات')->value('supervisor_id');
                $supplyUnitId = Unit::where('title','تدارکات')->value('id');

                if($unitSupervisorUpdate)
                {
                    $userId = DB::table('users')->insertGetId
                    ([
                        'title'             =>trim($request->title),
                        'name'              =>trim($request->name),
                        'family'            =>trim($request->family),
                        'username'          =>trim($request->username),
                        'password'          =>bcrypt($request->password),
                        'cellphone'         =>trim($request->cellphone),
                        'internal_phone'    =>trim($request->internal_phone),
                        'description'       =>trim($request->description),
                        'unit_id'           =>$request->unitId,
                        'is_supervisor'     => 1,
                        'created_at'        =>Carbon::now(new \DateTimeZone('Asia/Tehran'))

                    ]);
                    if($userId)
                    {
                        if($request->unitId == $supplyUnitId)
                        {
                            $updateHimSelf = User::where('id',$userId)->update(['supervisor_id' => $userId]);
                        }else
                        {
                            $updateHimSelf = User::where('id',$userId)->update(['supervisor_id' => $supervisorId]);
                        }
                        $updateUser  = User::where([['is_supervisor',0],['unit_id',$request->unitId]])->update(['supervisor_id' => $userId]);
                        $updateUsers = User::where('id',$unitSupervisorId)->update(['supervisor_id' => $userId]);
                        $updateUnit  = Unit::where('id',$request->unitId)->update(['supervisor_id' => $userId]);
                        if($updateUser && $updateUsers && $updateUnit || $updateHimSelf)
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
                $supervisorId = User::where([['unit_id',$request->unitId],['is_supervisor',1]])->value('id');

                $userId = DB::table('users')->insertGetId
                ([
                    'title'             =>trim($request->title),
                    'name'              =>trim($request->name),
                    'family'            =>trim($request->family),
                    'username'          =>trim($request->username),
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
                    'username'          =>trim($request->username),
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
                    $unitUpdate = Unit::where('id',$request->unitId)->update(['supervisor_id' => $supervisorId]);
                    if($unitUpdate)
                    {
                        return response('کاربر جدید درج شد');
                    }

                }
                break;

            case 4:
                $supervisorId = Unit::where('title','تدارکات')->value('supervisor_id');
                $userId = DB::table('users')->insertGetId
                ([
                    'title'             =>trim($request->title),
                    'name'              =>trim($request->name),
                    'family'            =>trim($request->family),
                    'username'          =>trim($request->username),
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
                        $unitUpdate   = Unit::where('id',$request->unitId)->update(['supervisor_id' => $userId]);
                        if($unitUpdate)
                        {
                            return response('کاربر جدید درج شد');
                        }
                    }else
                        {
                            return response('خطا در ثبت اطلاعات');
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
//                'email' => $request->email,
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
        $workers = Workers::where([['active',1],['user_id' , $userId]])->orderBy('date')->get();
        foreach ($workers as $worker) {
            $worker->date = $this->toPersian($worker->date);
            $worker->card = 'data:image/png;base64,'.$worker->card;
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
        if(preg_match('#^([0-9]?[0-9]?[0-9]{2}[ /.](0?[1-9]|1[012])[ /.](0?[1-9]|[12][0-9]|3[01]))*$#', $request->date))
        {
            if($request->hasFile('image'))
            {
                $extension = $request->image->getClientOriginalExtension();
                $fileSize  = $request->image->getClientSize();
                //  dd($fileSize);
                if($fileSize < 200000)
                {
                    if($extension == 'jpg' || $extension == 'JPG')
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
//                        $path = public_path() . '\\' . $request->image->getClientOriginalName();
                        $path = public_path() . '/' . $request->image->getClientOriginalName();
                        //dd($path);
                        $image = file_get_contents($path);
                        File::delete($path);
                        $fileName = encrypt(base64_encode($image));
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
                        return response('پسوند فایل کارت کارگری نامعتبر است');
                    }
                }else
                {
                    return response('حجم فایل کارت کارگری بیش از حد مجاز است');
                }

            }else
            {
                return response('لطفا فایل عکس کارگری خود را انتخاب نمایید ، سپس درخواست خود را وارد نمایید');
            }
        }else
        {
            return response('لطفا تاریخ را بطور صحیح وارد نمایید، مثلا : 1396/05/01');
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
        if(preg_match('#^([0-9]?[0-9]?[0-9]{2}[ /.](0?[1-9]|1[012])[ /.](0?[1-9]|[12][0-9]|3[01]))*$#', $request->date1) && preg_match('#^([0-9]?[0-9]?[0-9]{2}[ /.](0?[1-9]|1[012])[ /.](0?[1-9]|[12][0-9]|3[01]))*$#', $request->date2))
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
                    $data = Workers::whereBetween('date', [$gDate1, $gDate2])->where([['active', 1], ['user_id', $userId]])->orderBy('date')->get();
                    break;
            }

            foreach ($data as $date) {
                $date->date = $this->toPersian($date->date);
                $date->card = 'data:image/jpeg;base64,' . $date->card;
            }
            return response()->json(compact('data'));
        }else
        {
            return response()->json('لطفا تاریخ را بطور صحیح وارد کنید، مثلا : 1396/05/01');
        }

    }



//pr2
    public function productRequestManagement()
    {
        $pageTitle='مدیریت درخواست کالا تازه ثبت شده';
        $pageName='productRequestManagement';

        $me=Auth::user();
        if($me->unit_id==6 or $me->unit_id==9 or $me->unit_id==8 or $me->unit_id==10 or $me->unit_id==12 or $me->unit_id==4)
        {
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

            }
            $requestRecords=RequestRecord::where('step',$step)->pluck('request_id');

            $productRequests=Request2::where('request_type_id',3)->whereIn('id',$requestRecords)->orderBy('created_at','desc')->get();
//dd($productRequests);
            foreach($productRequests as $productRequest)
            {
                //undecided records
                $productRequest->request_record_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step',$step]])->count();
                //in the process records
                $productRequest->request_record_count_accept=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step','>=',$step2],['active',1]])->count();
                //inactive records
                $productRequest->request_record_count_refused=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null]])->count();
                $productRequest->date = $this->toPersian($productRequest->created_at);
            }
//        dd($productRequests);
            return view('admin.productRequestManagement', compact('pageTitle','productRequests','pageName'));
        }
        else
        return redirect('user/productRequest');
    }
//prr
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
        return view ('admin.productRequestRecords',compact('pageTitle','requestRecords','user'));
    }
//sr2
    public function serviceRequestManagement()
    {
        $pageTitle='مدیریت درخواست خدمت تازه ثبت شده';
        $pageName='productRequestManagement';

        $me=Auth::user();

        switch(trim($me->unit->title))
        {
            case 'تدارکات':
                $step=1;
                $step2=2;
                $check=1;
                break;
            case 'اعتبار':
                $step=2;
                $step2=3;
                $check=1;
                break;
            case 'امور عمومی':
                $step=3;
                $step2=4;
                $check=1;
                break;
            case 'ریاست':
                $step=4;
                $step2=5;
                $check=1;
                break;
            case 'امور مالی':
                $step=6;
                $step2=7;
                $check=1;
                break;
            default:
                //it means he/she is the unit user
                $check=0;
        }
        if($check==1)
        {
            $requestRecords=RequestRecord::where('step',$step)->pluck('request_id');
            $serviceRequests=Request2::where('request_type_id',2)->whereIn('id',$requestRecords)->get();
            //درخواست های من بعنوان مسئول واحد
            //یعنی مرحله پنجم درخواست خدمت که مسئول هر واحد متفاوته
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
                $serviceRequest->date = $this->toPersian($serviceRequest->created_at);
            }

            foreach($serviceRequests as $serviceRequest)
            {
                //undecided records
                $serviceRequest->request_record_count=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step',$step]])->count();
                //in the process records
                $serviceRequest->request_record_count_accept=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step','>=',$step2],['active',1]])->count();
                //inactive records
                $serviceRequest->request_record_count_refused=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id','!=',null]])->count();
                $serviceRequest->date = $this->toPersian($serviceRequest->created_at);
            }
            $serviceRequests=$serviceRequests->merge($serviceRequests2);
//        dd($serviceRequests);
        }
        else{
            $service_request_id=Request2::where([['unit_id',$me->unit_id],['request_type_id',2]])->pluck('id');
            $requestRecords=RequestRecord::where('step',5)->whereIn('request_id',$service_request_id)->pluck('request_id');
            $serviceRequests=Request2::whereIn('id',$requestRecords)->get();
            foreach($serviceRequests as $serviceRequest)
            {
                //undecided records
                $serviceRequest->request_record_count=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step',5]])->count();
                //in the process records
                $serviceRequest->request_record_count_accept=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step','>=',6],['active',1]])->count();
                //inactive records
                $serviceRequest->request_record_count_refused=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id','!=',null]])->count();
                $serviceRequest->date = $this->toPersian($serviceRequest->created_at);
            }
        }
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
                $check=1;
                break;
            case 'اعتبار':
                $step=2;
                $check=1;
                break;
            case 'امور عمومی':
                $step=3;
                $check=1;
                break;
            case 'ریاست':
                $step=4;
                $check=1;
                break;
            case 'امور مالی':
                $step=6;
                $check=1;
                break;
            default: $check=0;
        }
        if($check==1)
        {
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
        }
        else {
//به عنوان مسئول واحد
            $request_id=Request2::where([['unit_id',$user->unit_id],['id',$id]])->pluck('id');
            $requestRecords=RequestRecord::whereIn('request_id',$request_id)->where('step',5)->get();
            foreach($requestRecords as $requestRecord)
            {
                $requestRecord->mine=1;
                //decrypt
                if(!empty($requestRecord->title))
                    $requestRecord->title=decrypt($requestRecord->title);
                if(!empty($requestRecord->code))
                    $requestRecord->code=decrypt($requestRecord->code);
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
//            dd($requestRecords);
        }
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
                    $check=1;
                }
                else
                {
                    $step=8;
                    $step2=7;
                    $check=1;
                }
                break;
            case 'اعتبار':
                $step=3;
                $step2=2;
                $check=1;
                break;
            case 'امور عمومی':
                $step=4;
                $step2=3;
                $check=1;
                break;
            case 'ریاست':
                $step=5;
                $step2=4;
                $check=1;
                break;
            case 'امور مالی':
                $step=7;
                $step2=6;
                $check=1;
                break;
            default:
                $check=0;
        }
        if($check==1)
        {
            $requestRecords=RequestRecord::where([['step','>=',$step],['active',1],['refuse_user_id',null]])->pluck('request_id');
            $serviceRequests=Request2::where('request_type_id',2)->whereIn('id',$requestRecords)->orderBy('created_at','desc')->get();
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
                $serviceRequest->date = $this->toPersian($serviceRequest->created_at);
            }

            $serviceRequests=$serviceRequests->merge($serviceRequests2);

            foreach($serviceRequests as $serviceRequest)
            {
                //undecided records
                $serviceRequest->request_record_count=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step',$step2]])->count();
                //in the process records
                $serviceRequest->request_record_count_accept=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step','>=',$step],['active',1]])->count();
                //inactive records
                $serviceRequest->request_record_count_refused=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id','!=',null]])->count();
                $serviceRequest->date = $this->toPersian($serviceRequest->created_at);
            }
        }
        else{
            $service_request_id=Request2::where([['unit_id',$user->unit_id],['request_type_id',2]])->pluck('id');
            $requestRecords=RequestRecord::where([['step','>=',6]],['active',1],['refuse_user_id',null])->whereIn('request_id',$service_request_id)->pluck('request_id');
            $serviceRequests=Request2::whereIn('id',$requestRecords)->get();

            foreach($serviceRequests as $serviceRequest)
            {
                //undecided records
                $serviceRequest->request_record_count=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step',6]])->count();
                //in the process records
                $serviceRequest->request_record_count_accept=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id',null],['step','>=',5],['active',1]])->count();
                //inactive records
                $serviceRequest->request_record_count_refused=RequestRecord::where([['request_id',$serviceRequest->id],['refuse_user_id','!=',null]])->count();
                $serviceRequest->date = $this->toPersian($serviceRequest->created_at);
            }
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
        $productRequests=Request2::where('request_type_id',3)->whereIn('id',$requestRecords)->orderBy('created_at','desc')->get();
//        dd($productRequests);
        return view('admin.productRequestManagement', compact('pageTitle','productRequests','pageName'));
    }
    public function acceptProductRequestManagementGet()
    {
        $pageTitle='درخواست های در حال پیگیری';
        $pageName='acceptProductRequestManagement';
        $user=Auth::user();
        if($user->unit_id==6 or $user->unit_id==9 or $user->unit_id==8 or $user->unit_id==10 or $user->unit_id==12 or $user->unit_id==4) {
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
                default:
                    $check=0;
            }
            $requestRecords=RequestRecord::where([['step','>=',$step],['active',1],['refuse_user_id',null]])->pluck('request_id');
            $productRequests=Request2::where('request_type_id',3)->whereIn('id',$requestRecords)->orderBy('created_at','desc')->get();
            foreach($productRequests as $productRequest)
            {
                //undecided records
                $productRequest->request_record_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step',$step2]])->count();
                //in the process records
                $productRequest->request_record_count_accept=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null],['step','>=',$step],['active',1]])->count();
                //inactive records
                $productRequest->request_record_count_refused=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null]])->count();
                $productRequest->date = $this->toPersian($productRequest->created_at);
            }
        }
        else
            return redirect('user/productRequest');

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
            $card->card = 'data:image/jpeg;base64,'.decrypt($card->card);
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
        $ticketId = Message::where('id',$request->messageId)->value('ticket_id');
        $checkTicketToBeActive = Ticket::where('id',$ticketId)->value('active');
        if($checkTicketToBeActive == 0)
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
        }else
        {
            return response('با توجه به اینکه این تیکت بسته شده است ، امکان ارسال پیام وجود ندارد ');
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
        $productRequests=Request2::where('request_type_id',3)->orderBy('created_at','desc')->get();
        foreach($productRequests as $productRequest)
        {
            $all_count=RequestRecord::where('request_id',$productRequest->id)->count();
            $accept_count=RequestRecord::where([['request_id',$productRequest->id],['step',7],['refuse_user_id',null],['active',1]])->count();
            $has_certificate_count=RequestRecord::where([['request_id',$productRequest->id],['step',8]])->count();
            $refuse_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null],['active',0]])->count();
            if($all_count==($accept_count+$refuse_count))
            {
                $q=DB::table('requests')->where('id',$productRequest->id)->update([
                    'active'=>1
                ]);
            }

            $productRequest->all_count=$all_count;
            $productRequest->accept_count=$accept_count;
            $productRequest->has_certificate_count=$has_certificate_count;
            $productRequest->refuse_count=$refuse_count;
            $productRequest->date = $this->toPersian($productRequest->created_at);
            $certificate_has=Certificate::where('request_id',$productRequest->id)->get();
//            dd($certificate_has);
            if(empty($certificate_has[0]))
                $productRequest->hasCertificate=0;
            else
                $productRequest->hasCertificate=1;

            $certificates=Certificate::where('request_id',$productRequest->id)->get();

            foreach ($certificates as $certificate) {
                $all_c_count=CertificateRecord::where('certificate_id',$certificate->id)->count();
                $finished_c_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',5]])->count();
                if($all_c_count==$finished_c_count)
                {
                    $q=Certificate::where('id',$certificate->id)->update([
                        'active'=>1
                    ]);

                }
            }

        }
//dd($productRequests);
        return view('admin.productRequestManagement',compact('pageTitle','productRequests','pageName'));

    }

    public function confirmedRequestDetails($id)
    {
        if(Auth::user()->unit_id!=6 and Auth::user()->unit_id!=9)
            return redirect()->back();
        $pageTitle='جزئیات درخواست شماره : '.$id;
        $pageName='confirmedRequestDetails';
        $request=Request2::where([['id',$id],['active',1]])->get();
        $request_records=RequestRecord::where([['request_id',$id],['active',1]])->get();

        foreach($request as $item)
        {
            $item->request_records=$request_records;
            $item->date = $this->toPersian($item->created_at);

            $all_count=RequestRecord::where('request_id',$item->id)->count();
            $accept_count=RequestRecord::where([['request_id',$item->id],['step',7],['refuse_user_id',null],['active',1]])->count();
            $has_certificate_count=RequestRecord::where([['request_id',$item->id],['step',8]])->count();
            $refuse_count=RequestRecord::where([['request_id',$item->id],['refuse_user_id','!=',null],['active',0]])->count();

            $item->all_count=$all_count;
            $item->accept_count=$accept_count;
            $item->has_certificate_count=$has_certificate_count;
            $item->refuse_count=$refuse_count;

            $certificate_has=Certificate::where('request_id',$item->id)->get();
//            dd($certificate_has);
            if(empty($certificate_has[0]))
                $item->hasCertificate=0;
            else
                $item->hasCertificate=1;
        }
//dd($request);
        foreach($request_records as $requestRecord)
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

//            if(!empty($requestRecord->code))
//                $requestRecord->code=decrypt($requestRecord->code);
        } //

//dd($request);
        return view('admin.confirmedRequest',compact('pageTitle','pageName','request'));
    }

    //shiri : below function is related to show printed form of product request
    public function printProductRequest($id)
    {
//        $formExistence = Request2::where('id',$id)->value('request_form_id');
//        if($formExistence != 0)
//        {
//            $formContents = Form::where('id',$formExistence)->get();
//            $pageTitle = 'نسخه چاپی گواهی';
//            return view('admin.certificate.productRequestForm',compact('formContents','pageTitle'));
//        }
//        else {
        $storageId = Unit::where('title', 'انبار')->value('id');
        $storageSupervisorInfo = User::where([['unit_id', $storageId], ['is_supervisor', 1]])->get();
        $storageSupervisorId = 0;
        $storageSupervisorName = '';
        $storageSupervisorFamily = '';
        $storageSupervisorTitle = '';
        foreach ($storageSupervisorInfo as $storageSupervisorInf) {
            $storageSupervisorId += $storageSupervisorInf->id;
            $storageSupervisorName .= $storageSupervisorInf->name;
            $storageSupervisorFamily .= $storageSupervisorInf->family;
            $storageSupervisorTitle .= $storageSupervisorInf->title;
        }
        $storageSupervisorFullName = $storageSupervisorTitle.chr(10).$storageSupervisorName . chr(10) . $storageSupervisorFamily;
        $storageSupervisorSignature = Signature::where('user_id', $storageSupervisorId)->value('signature');
        if($storageSupervisorSignature != null)
        {
            $storageSupervisorSignature = 'data:image/png;base64,' . decrypt($storageSupervisorSignature);
        }

        $originalJobId = Unit::where('title', 'امور عمومی')->value('id');
        $originalJobSupervisorInfo = User::where([['unit_id', $originalJobId], ['is_supervisor', 1]])->get();
        $originalJobSupervisorId = 0;
        $originalJobSupervisorName = '';
        $originalJobSupervisorFamily = '';
        $originalJobSupervisorTitle = '';
        foreach ($originalJobSupervisorInfo as $originalJobSupervisorInf) {
            $originalJobSupervisorId += $originalJobSupervisorInf->id;
            $originalJobSupervisorName .= $originalJobSupervisorInf->name;
            $originalJobSupervisorFamily .= $originalJobSupervisorInf->family;
            $originalJobSupervisorTitle .= $originalJobSupervisorInf->title;
        }

        $originalJobSupervisorFullName =  $originalJobSupervisorTitle. chr(10) .$originalJobSupervisorName . chr(10) . $originalJobSupervisorFamily;
        $originalJobSupervisorSignature = Signature::where('user_id', $originalJobSupervisorId)->value('signature');
        if($originalJobSupervisorSignature != null)
        {
            $originalJobSupervisorSignature = 'data:image/png;base64,' . decrypt($originalJobSupervisorSignature);
        }


        $bossUnitId = Unit::where('title', 'ریاست')->value('id');
        $bossInfo = User::where([['unit_id', $bossUnitId], ['is_supervisor', 1]])->get();
        $bossId = 0;
        $bossName = '';
        $bossFamily = '';
        $bossTitle = '';
        foreach ($bossInfo as $bossInf) {
            $bossId += $bossInf->id;
            $bossName .= $bossInf->name;
            $bossFamily .= $bossInf->family;
            $bossTitle .= $bossInf->title;
        }
        $bossFullName = $bossTitle. chr(10) .$bossName . chr(10) . $bossFamily;
        $bossSignature = Signature::where('user_id', $bossId)->value('signature');
        if($bossSignature != null)
        {
            $bossSignature = 'data:image/png;base64,' . decrypt($bossSignature);
        }

        $creditUnitId = Unit::where('title', 'اعتبار')->value('id');
        $creditSupervisorInfo = User::where([['unit_id', $creditUnitId], ['is_supervisor', 1]])->get();
        $creditSupervisorId = 0;
        $creditSupervisorName = '';
        $creditSupervisorFamily = '';
        $creditSupervisorTitle = '';
        foreach ($creditSupervisorInfo as $creditSupervisorInf) {
            $creditSupervisorId += $creditSupervisorInf->id;
            $creditSupervisorName .= $creditSupervisorInf->name;
            $creditSupervisorFamily .= $creditSupervisorInf->family;
            $creditSupervisorTitle .= $creditSupervisorInf->title;
        }
        $creditSupervisorFullName =  $creditSupervisorTitle. chr(10) . $creditSupervisorName . chr(10) . $creditSupervisorFamily;
        $creditSupervisorSignature = Signature::where('user_id', $creditSupervisorId)->value('signature');
        if($creditSupervisorSignature != null)
        {
            $creditSupervisorSignature = 'data:image/png;base64,' . decrypt($creditSupervisorSignature);
        }


        $financeUnitId = Unit::where('title', 'امور مالی')->value('id');
        $financeSupervisorInfo = User::where([['unit_id', $financeUnitId], ['is_supervisor', 1]])->get();
        $financeSupervisorId = 0;
        $financeSupervisorName = '';
        $financeSupervisorFamily = '';
        $financeSupervisorTitle = '';
        foreach ($financeSupervisorInfo as $financeSupervisorInf) {
            $financeSupervisorId = $financeSupervisorInf->id;
            $financeSupervisorName = $financeSupervisorInf->name;
            $financeSupervisorFamily = $financeSupervisorInf->family;
            $financeSupervisorTitle = $financeSupervisorInf->title;
        }
        $financeSupervisorFullName = $financeSupervisorTitle. chr(10) .$financeSupervisorName . chr(10) . $financeSupervisorFamily;
        $financeSupervisorSignature = Signature::where('user_id', $financeSupervisorId)->value('signature');
        if($financeSupervisorSignature != null )
        {
            $financeSupervisorSignature = 'data:image/png;base64,' . decrypt($financeSupervisorSignature);
        }


        $pageTitle = 'نسخه چاپی گواهی';
        $productRequestRecords = RequestRecord::where([['request_id', $id], ['accept', 1]])->get();
        $sum = 0;
        foreach ($productRequestRecords as $productRequestRecord) {
            $sum += decrypt($productRequestRecord->rate) * $productRequestRecord->count;
            //dd($sum);
            if(!empty($productRequestRecord->title))
                $productRequestRecord->title=decrypt($productRequestRecord->title);
            if(!empty($productRequestRecord->description))
                $productRequestRecord->description=decrypt($productRequestRecord->description);
            if(!empty($productRequestRecord->unit_count))
                $productRequestRecord->unit_count=decrypt($productRequestRecord->unit_count);
            if(!empty($productRequestRecord->price))
                $productRequestRecord->price=decrypt($productRequestRecord->price);
            if(!empty($productRequestRecord->code))
                $productRequestRecord->code=decrypt($productRequestRecord->code);
            if(!empty($productRequestRecord->rate))
                $productRequestRecord->rate=decrypt($productRequestRecord->rate);
            if(!empty($productRequestRecord->why_not))
                $productRequestRecord->why_not=decrypt($productRequestRecord->why_not);
        }
//dd($productRequestRecords);
        return view('admin.certificate.productRequestForm', compact('pageTitle', 'productRequestRecords', 'sum', 'storageSupervisorSignature', 'originalJobSupervisorSignature', 'bossSignature', 'creditSupervisorSignature', 'financeSupervisorSignature', 'storageSupervisorFullName', 'originalJobSupervisorFullName', 'bossFullName', 'creditSupervisorFullName', 'financeSupervisorFullName'));
        //  }
    }

    //shiri : below function is related to print service_request_form
    public function printServiceRequest($id)
    {
        //dd('hello');
//        $formExistence = Request2::where('id',$id)->value('request_form_id');
//        if($formExistence != 0)
//        {
//            $formContents = Form::where('id',$formExistence)->get();
//            $pageTitle = 'نسخه چاپی گواهی';
//            return view('admin.certificate.serviceRequestForm',compact('formContents','pageTitle'));
//        }
//        else
//            {

        $supplyId = Unit::where('title', 'تدارکات')->value('id');
        $supplySupervisorInfo = User::where([['unit_id', $supplyId], ['is_supervisor', 1]])->get();
        $supplySupervisorId = 0;
        $supplySupervisorName = '';
        $supplySupervisorFamily = '';
        $supplySupervisorTitle = '';
        foreach ($supplySupervisorInfo as $supplySupervisorInf) {
            $supplySupervisorId += $supplySupervisorInf->id;
            $supplySupervisorName .= $supplySupervisorInf->name;
            $supplySupervisorFamily .= $supplySupervisorInf->family;
            $supplySupervisorTitle .= $supplySupervisorInf->title;
        }
        $supplySupervisorSignature = Signature::where('user_id', $supplySupervisorId)->value('signature');
        if($supplySupervisorSignature != null)
        {
            $supplySupervisorSignature = 'data:image/png;base64,' . decrypt($supplySupervisorSignature);
        }
            $supplySupervisorFullName = $supplySupervisorTitle. chr(10) .$supplySupervisorName . chr(10) . $supplySupervisorFamily;


        $bossUnitId = Unit::where('title', 'ریاست')->value('id');
        $bossInfo = User::where([['unit_id', $bossUnitId], ['is_supervisor', 1]])->get();
        $bossId = 0;
        $bossName = '';
        $bossFamily = '';
        $bossTitle = '';
        foreach ($bossInfo as $bossInf) {
            $bossId += $bossInf->id;
            $bossName .= $bossInf->name;
            $bossFamily .= $bossInf->family;
            $bossTitle .= $bossInf->title;
        }
        $bossFullName =$bossTitle. chr(10) . $bossName . chr(10) . $bossFamily;
        $bossSignature = Signature::where('user_id', $bossId)->value('signature');
        if($bossSignature != null)
        {
            $bossSignature = 'data:image/png;base64,' . decrypt($bossSignature);
        }

        $creditUnitId = Unit::where('title', 'اعتبار')->value('id');
        $creditSupervisorInfo = User::where([['unit_id', $creditUnitId], ['is_supervisor', 1]])->get();
        $creditSupervisorId = 0;
        $creditSupervisorName = '';
        $creditSupervisorFamily = '';
        $creditSupervisorTitle = '';

        foreach ($creditSupervisorInfo as $creditSupervisorInf) {
            $creditSupervisorId += $creditSupervisorInf->id;
            $creditSupervisorName .= $creditSupervisorInf->name;
            $creditSupervisorFamily .= $creditSupervisorInf->family;
            $creditSupervisorTitle .= $creditSupervisorInf->title;
        }

        $creditSupervisorFullName =  $creditSupervisorTitle. chr(10) .$creditSupervisorName . chr(10) . $creditSupervisorFamily;
        $creditSupervisorSignature = Signature::where('user_id', $creditSupervisorId)->value('signature');
        if($creditSupervisorSignature != null)
        {
            $creditSupervisorSignature = 'data:image/png;base64,' . decrypt($creditSupervisorSignature);
        }

        $financeUnitId = Unit::where('title', 'امور مالی')->value('id');
        $financeSupervisorInfo = User::where([['unit_id', $financeUnitId], ['is_supervisor', 1]])->get();
        $financeSupervisorId = 0;
        $financeSupervisorName = '';
        $financeSupervisorFamily = '';
        $financeSupervisorTitle = '';
        foreach ($financeSupervisorInfo as $financeSupervisorInf) {
            $financeSupervisorId = $financeSupervisorInf->id;
            $financeSupervisorName = $financeSupervisorInf->name;
            $financeSupervisorFamily = $financeSupervisorInf->family;
            $financeSupervisorTitle = $financeSupervisorInf->title;
        }
        $financeSupervisorFullName = $financeSupervisorTitle. chr(10) .$financeSupervisorName . chr(10) . $financeSupervisorFamily;
        $financeSupervisorSignature = Signature::where('user_id', $financeSupervisorId)->value('signature');
        if($financeSupervisorSignature != null)
        {
            $financeSupervisorSignature = 'data:image/png;base64,' . decrypt($financeSupervisorSignature);
        }

        $pageTitle = 'نسخه چاپی گواهی';
        $productRequestRecords = RequestRecord::where([['request_id', $id], ['accept', 1]])->get();
        $sum = 0;
        $unitName = '';
        $unitId = 0;
        $requestNumber = 0;
        $date = '';
        foreach ($productRequestRecords as $productRequestRecord) {
//            $productRequestRecord->rate=decrypt($productRequestRecord->rate);
            if(!empty($productRequestRecord->title))
                $productRequestRecord->title=decrypt($productRequestRecord->title);
            if(!empty($productRequestRecord->description))
                $productRequestRecord->description=decrypt($productRequestRecord->description);
            if(!empty($productRequestRecord->unit_count))
                $productRequestRecord->unit_count=decrypt($productRequestRecord->unit_count);
            if(!empty($productRequestRecord->price))
                $productRequestRecord->price=decrypt($productRequestRecord->price);
            if(!empty($productRequestRecord->rate))
                $productRequestRecord->rate=decrypt($productRequestRecord->rate);
            if(!empty($productRequestRecord->why_not))
                $productRequestRecord->why_not=decrypt($productRequestRecord->why_not);
            $sum += $productRequestRecord->rate * $productRequestRecord->count;
            //dd($sum);
            if ($unitName == '' && $requestNumber == 0 && $date == '' && $unitId == 0) {
                $unitName .= $productRequestRecord->request->unit->title;
                $requestNumber += $productRequestRecord->id;
                $date .= $this->toPersian($productRequestRecord->request->created_at->toDateString());
                $unitId += $productRequestRecord->request->unit_id;

            }
        }
    //date of request
        $request_id=$productRequestRecords[0]->request_id;
        $request=Request2::where('id',$request_id)->get();
        $request->date=$this->toPersian($request[0]->created_at->toDateString());

        $unitSupervisorInfo = User::where([['unit_id', $unitId], ['is_supervisor', 1]])->get();
        $unitSupervisorId = 0;
        $unitSupervisorName = '';
        $unitSupervisorFamily = '';
        $unitSupervisorTitle = '';
        foreach ($unitSupervisorInfo as $unitSupervisorInf) {
            $unitSupervisorId += $unitSupervisorInf->id;
            $unitSupervisorName .= $unitSupervisorInf->name;
            $unitSupervisorFamily .= $unitSupervisorInf->family;
            $unitSupervisorTitle .= $unitSupervisorInf->title;
        }
        $unitSupervisorFullName = $unitSupervisorTitle. chr(10) .$unitSupervisorName . chr(10) . $unitSupervisorFamily;
        $unitSupervisorSignature = Signature::where('user_id', $unitSupervisorId)->value('signature');
        if($unitSupervisorSignature != null)
        {
            $unitSupervisorSignature = 'data:image/png;base64,' . decrypt($unitSupervisorSignature);
        }

        return view('admin.certificate.serviceRequestForm', compact('request','productRequestRecords', 'pageTitle', 'sum', 'unitName', 'requestNumber', 'date', 'supplySupervisorSignature', 'originalJobSupervisorSignature', 'bossSignature', 'creditSupervisorSignature', 'financeSupervisorSignature', 'creditSupervisorFullName', 'financeSupervisorFullName', 'bossFullName', 'supplySupervisorFullName', 'unitSupervisorName', 'unitSupervisorFullName', 'unitSupervisorSignature'));
        //  }
    }

    //shiri : below function is related to export delivery and install certificate
    public function exportDeliveryInstallCertificate($id)
    {
        $pageTitleInstall = 'صدور گواهی تحویل و نصب';
        $pageTitleUse     = 'صدور گواهی تحویل و نصب';
//        $oldCertificates = Form::where('certificate_id',$id)->get();
//        if(count($oldCertificates) > 0)
//        {
//            return view('admin.certificate.exportDeliveryInstallCertificate',compact('pageTitleInstall','oldCertificates','pageTitleUse'));
//        }
//        else
//            {
        //چک میکنه اگه تمام رکوردهای گواهی تایید شده هستند صفحه چاپ را نمایش میدهد.
        $all_count=CertificateRecord::where('certificate_id',$id)->count();

        $accept_count=CertificateRecord::where([['certificate_id',$id],['step',5]])->count();
        if($all_count!=$accept_count)
            return redirect('admin/showCertificates/'.$id);

        $bossUnitId = Unit::where('title','ریاست')->value('id');
        $bossInfo = User::where([['unit_id',$bossUnitId],['is_supervisor',1]])->get();
        $bossId = 0;
        $bossName = '';
        $bossFamily = '';
        $bossTitle='';
        foreach ($bossInfo as $bossInf)
        {
            $bossId += $bossInf->id;
            $bossName .= $bossInf->name;
            $bossFamily .= $bossInf->family;
            $bossTitle .= $bossInf->title;
        }
        $bossFullName = $bossTitle.chr(10).$bossName .chr(10).$bossFamily;
        $bossSignature = Signature::where('user_id',$bossId)->value('signature');
        $bossSignature = 'data:image/png;base64,'.decrypt($bossSignature);


        $certificates = Certificate::where('id',$id)->get();
        $shopComp  = '';
        $requestId = 0;
        $date      = '';
        $certificateId = 0;
        foreach ($certificates as $certificate)
        {
            $shopComp       .= decrypt($certificate->shop_comp);
            $requestId      += $certificate->request_id;
            $date           .= $this->toPersian($certificate->created_at->toDateString());
            $certificateId  += $certificate->id;
        }
        //$certificateId        = Certificate::where('request_id',$id)->pluck('id');
        $unitId               = Request2::where('id',$requestId)->value('unit_id');
        $certificateRecords = CertificateRecord::where('certificate_id',$id)->get();
        foreach($certificateRecords as $certificateRecord)
        {
            if(!empty($certificateRecord->price))
            $certificateRecord->price=decrypt($certificateRecord->price);
            if(!empty($certificateRecord->rate))
            $certificateRecord->rate=decrypt($certificateRecord->rate);
            if(!empty($certificateRecord->unit_count))
            $certificateRecord->unit_count=decrypt($certificateRecord->unit_count);
        }
//        dd($certificateRecords);
        $unitName = Unit::where('id',$unitId)->value('title');
        $sum = 0;
        $receiverId = 0;
        $receiverName   = '';
        $receiverFamily = '';
        $receiverTitle = '';
        $supplierId     = 0;
        foreach ($certificateRecords as $certificateRecord)
        {
            $sum += $certificateRecord->count * $certificateRecord->rate;
            if($receiverName == '' && $receiverFamily == '')
            {
                $receiverId     += $certificateRecord->user->id;
                $receiverName   .= $certificateRecord->user->name;
                $receiverFamily .= $certificateRecord->user->family;
                $receiverTitle .= $certificateRecord->user->title;
                $supplierId     += $certificateRecord->certificate->request->supplier_id;
            }

        }

        $receiverSignature = Signature::where('user_id',$receiverId)->value('signature');
        $receiverSignature = 'data:image/png;base64,'.decrypt($receiverSignature);
        $receiverFullName = $receiverTitle.chr(10).$receiverName .chr(10).$receiverFamily;


        $supplierName = User::where('id',$supplierId)->value('name');
        $supplierFamily = User::where('id',$supplierId)->value('family');
        $supplierTitle = User::where('id',$supplierId)->value('title');
        $supplierFullName = $supplierTitle.chr(10).$supplierName .chr(10).$supplierFamily;
        $supplierSignature = Signature::where('user_id',$supplierId)->value('signature');
        $supplierSignature = 'data:image/png;base64,'.decrypt($supplierSignature);

        $unitSupervisorInfo = User::where([['unit_id',$unitId],['is_supervisor',1]])->get();
        $unitSupervisorId = 0;
        $unitSupervisorName = '';
        $unitSupervisorFamily = '';
        $unitSupervisorTitle = '';
        foreach ($unitSupervisorInfo as $unitSupervisorInf)
        {
            $unitSupervisorId     +=$unitSupervisorInf->id;
            $unitSupervisorName   .= $unitSupervisorInf->name;
            $unitSupervisorFamily .= $unitSupervisorInf->family;
            $unitSupervisorTitle .= $unitSupervisorInf->title;
        }
        $unitSupervisorFullName = $unitSupervisorTitle.chr(10).$unitSupervisorName .chr(10).$unitSupervisorFamily;
        $unitSupervisorSignature = Signature::where('user_id',$unitSupervisorId)->value('signature');
        $unitSupervisorSignature = 'data:image/png;base64,'.decrypt($unitSupervisorSignature);
        return view('admin.certificate.exportDeliveryInstallCertificate',compact('unitSupervisorSignature','supplierFullName','supplierSignature','unitSupervisorFullName','receiverSignature','receiverFullName','bossSignature','bossFullName','pageTitleInstall','pageTitleUse','certificateRecords' , 'sum','unitSupervisorName','unitSupervisorFamily','shopComp','unitName','receiverName','receiverFamily','certificateId','date'));
        //  }
    }


    //shiri : below function is related to save forms
    public function formSave(Request $request,$id)
    {

        $userId = Auth::user()->id;
        switch ($id) {
            case 1:
                $oldFactorFormId = Request2::where('id',$request->requestId)->value('factor_form_id');
                if($oldFactorFormId > 0)
                {
                    $update = Form::where('id',$oldFactorFormId)->increment('print_count');
                    $formPrintId = DB::table('print_form')->insertGetId
                    ([

                        'form_id'    => $oldFactorFormId,
                        'printed_by' => $userId,
                    ]);
                    if ($update && $formPrintId) {
                        return response('لطفا برای چاپ فرم کلیک نمایید');
                    }
                }else
                {
                    $newFactorFormId = DB::table('forms')->insertGetId
                    ([
                        'title'       => 'فرم خلاصه تنظیمی',
                        'request_id'  => $request->requestId,
                        'print_count' =>  1
                    ]);
                    if($newFactorFormId > 0)
                    {
                        $factorFormId = Request2::where('id',$request->requestId)->update(['factor_form_id' => $newFactorFormId]);
                        $formPrintId = DB::table('print_form')->insertGetId
                        ([

                            'form_id'    => $newFactorFormId,
                            'printed_by' => $userId,
                        ]);
                        if ($factorFormId && $formPrintId) {
                            return response('لطفا برای چاپ فرم کلیک نمایید');
                        }
                    }
                }
                break;

            case 2:
                $oldRequestFormId = Request2::where('id',$request->requestId)->value('request_form_id');
                if($oldRequestFormId > 0)
                {
                    $update = Form::where('id',$oldRequestFormId)->increment('print_count');
                    $formPrintId = DB::table('print_form')->insertGetId
                    ([

                        'form_id'    => $oldRequestFormId,
                        'printed_by' => $userId,
                    ]);
                    if ($update && $formPrintId) {
                        return response('لطفا برای چاپ فرم کلیک نمایید');
                    }

                }else
                {
                    $newRequestFormId = DB::table('forms')->insertGetId
                    ([
                        'title'       => 'فرم درخواست',
                        'request_id'  => $request->requestId,
                        'print_count' =>  1
                    ]);
                    if($newRequestFormId > 0)
                    {
                        $requestFormId = Request2::where('id',$request->requestId)->update(['request_form_id' => $newRequestFormId]);
                        $formPrintId = DB::table('print_form')->insertGetId
                        ([

                            'form_id'    => $newRequestFormId,
                            'printed_by' => $userId,
                        ]);
                        if ($requestFormId && $formPrintId) {
                            return response('لطفا برای چاپ فرم کلیک نمایید');
                        }
                    }
                }
                break;
            case 3:
                $oldFormId = Form::where('certificate_id', $request->certificateId)->value('id');
                if ($oldFormId > 0) {
                    $count = Form::where('certificate_id', $request->certificateId)->increment('print_count');
                    $formPrintId = DB::table('print_form')->insertGetId
                    ([
                        'form_id'    => $oldFormId,
                        'printed_by' => $userId,
                    ]);
                    if ($count && $formPrintId) {
                        return response('لطفا برای چاپ فرم کلیک نمایید');

                    }
                } else {
                    $newFormId = DB::table('forms')->insertGetId
                    ([
                        'request_id'     => $request->requestId,
                        'title'          => $request->title,
                        'certificate_id' => $request->certificateId,
                        'print_count'    => 1
                    ]);
                    if ($newFormId) {

                        $formPrintId = DB::table('print_form')->insertGetId
                        ([
                            'form_id'    => $newFormId,
                            'printed_by' => $userId,
                        ]);
                        if ($formPrintId) {
                            return response('لطفا برای چاپ فرم کلیک نمایید');
                        }

                    }
                }

                break;
            case 4:
                $oldFormId = Form::where('certificate_id', $request->certificateId)->value('id');
                if (count($oldFormId) > 0) {
                    $count = Form::where('certificate_id', $request->certificateId)->increment('print_count');
                    $formPrintId = DB::table('print_form')->insertGetId
                    ([
                        'form_id'    => $oldFormId,
                        'printed_by' => $userId,
                    ]);
                    if ($count && $formPrintId) {
                        return response('لطفا برای چاپ فرم کلیک نمایید');
                    }
                } else {
                    $newFormId = DB::table('forms')->insertGetId
                    ([
                        'request_id'     => $request->requestId,
                        'title'          => 'گواهی تحویل خدمت',
                        'certificate_id' => $request->certificateId,
                        'print_count'    => 1
                    ]);
                    if ($newFormId) {
                        $formPrintId = DB::table('print_form')->insertGetId
                        ([
                            'form_id' => $newFormId,
                            'printed_by' => $userId,
                        ]);
                        if ($formPrintId) {
                            return response('لطفا برای چاپ فرم کلیک نمایید');
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
        $productRequests=Request2::where('request_type_id',2)->orderBy('created_at','desc')->get();
        foreach($productRequests as $productRequest)
        {
            $all_count=RequestRecord::where('request_id',$productRequest->id)->count();
            $accept_count=RequestRecord::where([['request_id',$productRequest->id],['step',7],['refuse_user_id',null],['active',1]])->count();
            $has_certificate_count=RequestRecord::where([['request_id',$productRequest->id],['step',8]])->count();
            $refuse_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null],['active',0]])->count();
            if($all_count==($accept_count+$refuse_count))
            {
                $q=DB::table('requests')->where('id',$productRequest->id)->update([
                    'active'=>1
                ]);
//                $productRequest->msg='Yes';

            }
//
//            else
//                $productRequest->msg='No';
            $productRequest->all_count=$all_count;
            $productRequest->accept_count=$accept_count;
            $productRequest->has_certificate_count=$has_certificate_count;
            $productRequest->refuse_count=$refuse_count;

            $productRequest->date = $this->toPersian($productRequest->created_at);
            $certificate_has=Certificate::where('request_id',$productRequest->id)->get();
//            dd($certificate_has);
            if(empty($certificate_has[0]))
                $productRequest->hasCertificate=0;
            else
                $productRequest->hasCertificate=1;
            $certificates=Certificate::where('request_id',$productRequest->id)->get();
            foreach ($certificates as $certificate) {
                $all_c_count=CertificateRecord::where('certificate_id',$certificate->id)->count();
                $finished_c_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',5]])->count();
                if($all_c_count==$finished_c_count)
                {
                    $q=Certificate::where('id',$certificate->id)->update([
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
        $rate      = encrypt($request->rate);
        $price     = encrypt($request->price);
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
                $unit_id=$request[0]->unit_id;
                $user_id=User::where([['is_supervisor',1],['unit_id',$unit_id]])->pluck('id');
//                return response($user_id);
                if($request[0]->unit->title=='تدارکات' or $user_id[0]==$request[0]->user_id)
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
            $request_id=RequestRecord::where('id',$id)->pluck('request_id');
            $unit_id=Request2::where('id',$request_id[0])->pluck('unit_id');
            if($step_record[0]==5 and $unit_id[0]!=12)
                $step=6;
            elseif($step_record[0]==5 and $unit_id[0]==12)
            {
                $step=7;
                $accept=1;
            }
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
        $certificates = Certificate::where([['request_id',$id],['active',1]])->get();
        foreach($certificates as $certificate)
        {
//decrypt
            if(!empty($certificate->shop_comp))
                $certificate->shop_comp=decrypt($certificate->shop_comp);
        }
        return view('admin.certificate.showCertificates',compact('certificates','pageTitle'));
    }

    //
    public function printServiceDeliveryForm($id)
    {
        //dd($id);
//        $formExistence = Form::where('certificate_id',$id)->get();
//        if(count($formExistence) > 0)
//        {
//          //  dd($formExistence);
//            $pageTitle = 'صدور گواهی تحویل و نصب';
//            return view('admin.certificate.serviceDeliveryForm',compact('formExistence','pageTitle'));
//        }
//        else {
//چک میکنه اگه تمام رکوردهای گواهی تایید شده هستند صفحه چاپ را نمایش میدهد.
        $all_count=CertificateRecord::where('certificate_id',$id)->count();
        $accept_count=CertificateRecord::where([['certificate_id',$id],['step',5],['active',1]])->count();
//        return $all_count.' / '.$accept_count;
        if($all_count!=$accept_count)
            return redirect()->back();


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
        $supplySupervisorSignature = 'data:image/png;base64,' . decrypt($supplySupervisorSignature);
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
        $bossSignature = 'data:image/png;base64,' . decrypt($bossSignature);


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
            $sum += $certificateRecord->count * decrypt($certificateRecord->rate);
            if ($receiverName == '' && $receiverFamily == '') {
                $receiverId += $certificateRecord->user->id;
                $receiverName .= $certificateRecord->user->name;
                $receiverFamily .= $certificateRecord->user->family;
                $requestId += $certificateRecord->certificate->request_id;
            }

        }
        $receiverSignature = Signature::where('user_id', $receiverId)->value('signature');
        $receiverSignature = 'data:image/png;base64,' . decrypt($receiverSignature);
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
        $unitSupervisorSignature = 'data:image/png;base64,' . decrypt($unitSupervisorSignature);
        return view('admin.certificate.serviceDeliveryForm', compact('supplySupervisorFullName', 'bossFullName', 'bossSignature', 'supplySupervisorSignature', 'unitSupervisorFullName', 'unitSupervisorSignature', 'certificateRecords', 'shopComp', 'requestId', 'pageTitle', 'unitName', 'receiverFullName', 'sum', 'receiverSignature', 'requestId'));
        //  }
    }

    //shiri : below function is related to print summary of requests
    public function printFactors($id)
    {
//        $formExistence = Request2::where('id',$id)->value('factor_form_id');
//        if($formExistence != 0)
//        {
//            $formContents = Form::where('id',$formExistence)->get();
//            $pageTitle = 'چاپ خلاصه تنظیمی';
//            return view('admin.certificate.factorsForm',compact('formContents','pageTitle'));
//        }
//        else
//            {
        $pageTitle = 'چاپ خلاصه تنظیمی';
        $bills = Bill::where('request_id',$id)->get();
        $sum = 0;
        $supplierId = 0;
        foreach ($bills as $bill)
        {
            $sum += decrypt($bill->final_price);
            if($supplierId == 0)
            {
                $supplierId += $bill->request->supplier_id;
            }

        }
        $supplierName = User::where('id',$supplierId)->value('name');
        $supplierFamily = User::where('id',$supplierId)->value('family');
        $supplierFullName = $supplierName .chr(10).$supplierFamily;
        $supplierSignature = Signature::where('user_id',$supplierId)->value('signature');
        if($supplierSignature != null)
        {
            $supplierSignature = 'data:image/png;base64,' . decrypt($supplierSignature);
        }
//        dd(count($supplierSignature));
        return view ('admin.certificate.factorsForm',compact('pageTitle','bills','sum','supplierFullName','supplierSignature'));
        //    }

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
                $sumGeneralPrice += decrypt($costDocumentsRecord->general_price);
                $sumDeduction    += decrypt($costDocumentsRecord->deduction);
                $sumPayedPrice   += decrypt($costDocumentsRecord->payed_price);
            }
            return view('admin.certificate.costDocumentForm',compact('costDocumentsRecords','pageTitle','sumDeduction','sumPayedPrice','sumGeneralPrice'));
        }else
        {
            return view('admin.costDocumentRegister',compact('id','pageTitle'));
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
                            'code'             => trim(encrypt($request->code[$i])),
                            'description'      => trim(encrypt($request->description[$i])),
                            'moein_office'     => trim(encrypt($request->moeinOffice[$i])),
                            'general_price'    => trim(encrypt($request->generalPrice[$i])),
                            'deduction'        => trim(encrypt($request->deduction[$i])),
                            'payed_price'      => trim(encrypt($request->payedPrice[$i])),
                            'page'             => trim(encrypt($request->page[$i])),
                            'row'              => trim(encrypt($request->row[$i])),
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

    public function issueBillManagementGet()
    {
        if(Auth::user()->unit_id==9)
        {
            $pageTitle='صدور قبض انبار';
            $pageName='issueBillManagement';
            $requests=Request2::where([['request_type_id',3],['active',1]])->get();
            foreach($requests as $request)
            {
                $request->records=RequestRecord::where('request_id',$request->id)->get();

            }
            return view('admin.issueBillManagement',compact('pageTitle','pageName','requests'));
        }
        else
            return back();
    }
    public function issueBillGet($id)
    {
        $pageTitle='صدور فاکتور';
        $pageName='issueBill';

        $records=RequestRecord::where([['request_id',$id],['accept',1]])->get();
        return view('admin.issueBill',compact('pageTitle','pageName','id','records'));

    }

    public function billUpload($id)
    {
        $pageTitle = 'آپلو فاکتور';
        return view('admin.billUpload',compact('pageTitle','id'));
    }

    public function addBillPhoto($request)
    {

        if(preg_match('#^([0-9]?[0-9]?[0-9]{2}[ /.](0?[1-9]|1[012])[ /.](0?[1-9]|[12][0-9]|3[01]))*$#', $request->date))
        {
            $image = $request->image;
            $src   = $request->requestId.'-'.str_random(4).$image->getClientOriginalName();
            $image->move( 'public/dashboard/image/' , $src);
            $fileExistence = public_path().'public/dashboard/image/'.$src;

            if($fileExistence)
            {
                $jDate = $request->date;
                if ($date = explode('/', $jDate)) {
                    $year = $date[0];
                    $month = $date[1];
                    $day = $date[2];
                }
                $gDate = $this->jalaliToGregorian($year, $month, $day);
                $gDate1 = $gDate[0] . '-' . $gDate[1] . '-' . $gDate[2];
                $now = new Carbon();
                $now = $now->toDateString();

                $factorId = DB::table('bills')->insertGetId
                ([
                    'src'           => $src,
                    'date'          => $gDate1,
                    'factor_number' => trim(encrypt($request->factorNumber)),
                    'user_id'       => Auth::user()->id,
                    'request_id'    => $request->requestId,
                    'final_price'   => encrypt($request->newFinalPrice),
                    'created_at'    => Carbon::now(new \DateTimeZone('Asia/Tehran'))
                ]);
                if($factorId)
                {
                    return response('فایل فاکتور مورد نظر شما آپلود گردید ، در صورت نیاز میتوانید فاکتورهای دیگر را آپلود کنید');
                }else
                {
                    return response('خطا در ثبت اطلاعات ، تماس با بخش پشتیبانی');
                }


            }else
            {
                return response('خطا در آپلود فایل فاکتور ، تماس با بخش پشتیبانی');
            }
        }else
        {
            return response('لطفا تاریخ را بطور صحیح وارد کنید، مثلا : 1396/05/01');
        }

    }


    public function preparedSummarize($id)
    {
        $pageTitle = 'ثبت خلاصه تنظیمی';
        $factors = DB::table('bills')->where('request_id',$id)->get();
        foreach($factors as $factor)
        {
            $factor->factor_number=decrypt($factor->factor_number);
            $factor->final_price=decrypt($factor->final_price);
        }
        return view('admin.preparedSummarize',compact('pageTitle','factors'));
    }
//
    public function savePreparedSummarize(Request $request)
    {
        //dd($request->totalPrice);
        if(!$request->ajax())
        {
            abort(403);
        }
        else
        {
            $check = DB::table('bills')->where('request_id',$request->requestId)->pluck('active')->toArray();
            if(in_array(0,$check))
            {
                $recordCount = $request->recordCount;
                if($recordCount != 0)
                {

                    $i =0;
                    while($i < $recordCount)
                    {
                        $query = DB::table('bills')->insert
                        ([

                            'final_price'            => encrypt(str_replace(',','',$request->totalPrice[$i])),
                            'factor_number'          => encrypt($request->description[$i]),
                            'user_id'                => Auth::user()->id,
                            'request_id'             => $request->requestId,
                            'created_at'             => Carbon::now(new \DateTimeZone('Asia/Tehran'))
                        ]);
                        $i++;
                    }
                    if($query)
                    {
                        $update = DB::table('bills')->where('request_id',$request->requestId)->update(['active' => 1]);
                        if($update)
                        {
                            return response('اطلاعات با موفقیت ثبت گردید');
                        }else
                        {
                            return response('خطا در ثبت اطلاعات ، تماس با بخش پشتیبانی');
                        }

                    }else
                    {
                        return response('خطا در ثبت اطلاعات ، تماس با بخش پشتیبانی');
                    }
                }
                else
                {
                    return response('ابتدا فرم مربوطه را پر نمایید ، سپس درخواست خود را ثبت نمایید');
                }
            }else
            {
                return response('خلاصه تنظیمی قبلا برای این درخواست ثبت گردیده است ، لطفا درخواست مجدد نفرمایید');
            }

        }

    }

    public function updatePreparedSummarize(Request $request)
    {
        if(!$request->ajax())
        {
            abort(403);
        }else
        {
            $check  = DB::table('bills')->where('request_id',$request->requestId)->pluck('active')->toArray();
            if(in_array(0,$check))
            {
                $update = DB::table('bills')->where('request_id',$request->requestId)->update(['active' => 1]);
                if($update)
                {
                    return response('خلاصه تنظیمی برای این درخواست ثبت گردید');
                }else
                {
                    return response('خطایی رخ داده است ، تماس با بخش پشتیبانی');
                }
            }else
            {
                return response('خلاصه تنظیمی قبلا برای این درخواست ثبت گردیده است ، لطفا درخواست مجدد نفرمایید');
            }

        }

    }

    public function checkPreparedSummarize(Request $request)
    {
        $count = DB::table('bills')->where('request_id',$request->requestId)->count();
        return response($count);
    }

    public function warehouseBill($id)
    {
        $pageTitle = "صدرو قبض انبار";
        $records = RequestRecord::where([['request_id',$id],['accept',1]])->get();
        return view('admin.warehouseBill',compact('pageTitle','records','id'));
    }

    public function checkFile($parameter,Request $request)
    {
        if(!$request->ajax())
        {
            abort(403);
        }
        else
        {
            if($request->hasFile('image'))
            {
                $extension = $request->image->getClientOriginalExtension();
                $size      = $request->image->getClientSize();
                if($request->hasFile('image'))
                {
                    if ($extension == 'png' || $extension == 'PNG' || $extension == 'jpg' || $extension == 'JPG')
                    {
                        if ($size < 150000)
                        {
                            switch ($parameter)
                            {
                                case 'warehouse':
                                    return $this->addWarehousePhoto($request);
                                    break;

                                case 'bill' :
                                    return $this->addBillPhoto($request);
                            }
                        }
                        else
                        {
                            return response('سایز فایل انتخاب شده بیش از حد مجاز میباشد');
                        }
                    }else
                    {
                        return response('پسوند فایل انتخاب شده معتبر نیست');
                    }
                }else
                {
                    return response('ابتدا فرم مربوطه را پر نمایید ، سپس درخواست خود را ثبت نمایید');
                }
            }else
            {
                return response('لطفا فایلی انتخاب نمایید ، سپس درخواست خود را ثبت نمایید');
            }

        }
    }

    public function addWarehousePhoto($request)
    {
        $check = Warehouse::where('request_id',$request->requestId)->count();
        if($check == 1)
        {
            return response('قبض انبار برای این درخواست قبلا ثبت گردیده است ، لطفا درخواست مجدد نفرمایید');
        }
        else
        {

            $image = $request->image;
            $src   = $request->requestId.'-'.str_random(4).$image->getClientOriginalName();
            $image->move( 'public/dashboard/image/' , $src);
            $fileExistence = public_path().'public/dashboard/image/'.$src;

            if($fileExistence)
            {

                $warehouseId = DB::table('warehouse')->insertGetId
                ([
                    'src'           => $src,
                    'factor_number' => trim(encrypt($request->factorNumber)),
                    'user_id'       => Auth::user()->id,
                    'request_id'    => $request->requestId,
                    'created_at'    => Carbon::now(new \DateTimeZone('Asia/Tehran'))
                ]);
                if($warehouseId)
                {
                    return response('قبض انبار  مورد نظر شما آپلود گردید');
                }else
                {
                    return response('خطا در ثبت اطلاعات ، تماس با بخش پشتیبانی');
                }
            }else
            {
                return response('خطا در آپلود فایل فاکتور ، تماس با بخش پشتیبانی');
            }
        }

    }

}
