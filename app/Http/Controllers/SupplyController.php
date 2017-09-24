<?php

namespace App\Http\Controllers;
//use Illuminate\Http\File;
use App\Http\Requests\AcceptServiceRequestValidation;
use App\Models\Request2;
use App\Models\RequestRecord;
use App\Models\Workers;
use App\User;
use App\Models\Unit;



use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;



class SupplyController extends Controller
{
    //

    /* shiri
       below function is to show  all the uncertain service requests to the admin of supply
     */

    public function arr()
    {

    }

    public function recentlyAddedService()
    {
        $pageTitle = 'درخواست های خدمت';
        $requests  = Request2::where([['request_type_id',2],['active',0]])->get();
        return view ('admin.recentlyAddedService', compact('pageTitle','requests'));
    }


//

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
//        dd($requests);
        return view ('admin.serviceInProcess', compact('pageTitle','requests'));
    }

    /*shiri
        below function is related to  show all request records and those details...
      */
    public function serviceShowDetails($id)
    {
        $pageTitle = 'جزییات درخواست های خدمت';
        $records = RequestRecord::where([['request_id',$id],['step',1],['active',0],['refuse_user_id',null]])->get();
        return view ('admin.serviceShowDetails',compact('pageTitle','records'));
    }

    /* shiri
        below function is related to accept the requested service
    */
    public function acceptServiceRequest(AcceptServiceRequestValidation $request)
    {
        if(!$request->ajax())
        {
            abort(403);
        }
        $id        = $request->id;
        $rate      = $request->rate;
        $price     = $request->price;
        $requestId = $request->requestId;

        $array = array('rate'=>$rate , 'price' => $price , 'step'=> 2 , 'active' => 1);
        $requestRecord = new RequestRecord();
        $update = $requestRecord->where('id',$id)->update($array);
        if($update)
        {
            $req = new Request2();
            $req->where('id',$requestId)->increment('request_opt');
            $requestOpt = $req->where('id',$requestId)->value('request_opt');
            $requestQty = $req->where('id',$requestId)->value('request_Qty');
            if($requestOpt === $requestQty)
            {
                $req->where('id',$requestId)->update(['active' => 1]);
            }
            return response('نرخ و قیمت ثبت گردید و درخواست خدمت به مرحله بعد ارسال شد');
        }


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
        $refuseId = Auth::user()->id;
        $array = array('why_not'=>$request->whyNot , 'step'=> 2 , 'refuse_user_id' => $refuseId);
        $requestRecord = new RequestRecord();
        $update = $requestRecord->where('id',$request->id)->update($array);
//        return response()->json('hello');
        if($update)
        {
            $req = new Request2();
            $req->where('id',$request->requestId)->increment('refuse_record_count');
            $requestOpt = $req->where('id',$request->requestId)->value('request_opt');
//            $requestQty = $req->where('id',$request->requestId)->value('request_Qty');
//            if($requestOpt === $requestQty)
//            {
//                $req->where('id',$request->requestId)->update(['active' => 1]);
//            }
//            return response('درخواست مربوطه با ثبت دلیل رد شد');
        }

    }


    //rayat//show user create form
    public function usersCreateGet()
    {
        $units = Unit::all();
        return view('admin.usersCreate')->with('units', $units);
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
    public function usersCreatePost(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required|max:100',
                'family' => 'required|max:100',
                'email' => 'required|email|max:100|unique:users',
                'password' => 'required|min:6|max:25|confirmed',
                'cellphone' => 'required|numeric',
                'internal_phone' => 'required|numeric',
                'unit_id' => 'required',
                'supervisor_id' => 'required',
                //'description' => 'required',
            ]
            ,
            [
                'name.required' => ' فیلد نام الزامی است',
                'name.max' => ' فیلد نام حداکثر باید 100 کاراکتر باشد ',
                'family.max' => ' فیلد نام خانوادگی عبور حداکثر باید 100 کاراکتر باشد ',
                'family.required' => ' فیلد نام خانوادگی الزامی است ',
                'email.required' => ' فیلد ایمیل الزامی است',
                'email.unique' => 'این ایمیل قبلا ثبت شده است',
                'email.Email' => ' فرمت ایمیل نادرست است ',
                'email.size' => ' فیلد ایمیل حداکثر باید 100 کاراکتر باشد ',
                'password.required' => ' فیلد رمز عبور الزامی است ',
                'password.confirmed' => ' فیلد رمز عبور  و تکرار آن با هم مطابقت ندارند ',
                'password.min' => ' فیلد رمز عبور حداقل باید 6 کاراکتر باشد ',
                'password.max' => ' فیلد رمز عبور حداکثر باید 25 کاراکتر باشد ',
                'cellphone.required' => ' فیلد موبایل الزامی است ',
                'cellphone.numeric' => ' فیلد موبایل عددی است',
                'internal_phone.required' => ' فیلد شماره داخلی الزامی است ',
                'internal_phone.numeric' => ' فیلد شماره داخلی عددی است',
                'internal_phone.size' => ' فیلد شماره داخلی باید 11 رقمی باشد',
                'unit_id.required' => ' فیلد شماره واحد الزامی است',
                'supervisor_id.required' => ' فیلد سرپرست الزامی است',
                //'description.required' => ' فیلد توضیحات الزامی است',
            ]);
        $input = $request->all();
        User::create($input);
        return response()->json();
    }

    //rayat//show user create form
    public function unitsCreateGet()
    {
        return view('admin.unitsCreate');
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
        $unit->phone = $request->title;
        $unit->description = $request->description;
        $unit->organization_id = 1;
        $res = $unit->save();
        return response()->json($res);
    }

    //Rayat//show unit manage
    public function unitsManageGet()
    {
        $data = Unit::all();
        return view('admin.unitsManage', compact('data'));
    } //Rayat//show user manage

    public function usersManageGet()
    {
        $data = User::all();
        return view('admin.usersManage', compact('data'));
    }

    //Rayat//active user in usersManage
    public function statusUser(Request $request)
    {
        $id = $request->id;
        $active = User::where('id', $id)->value('active');
        if ($active == 0) {
            User::where('id', $id)->update(['active' => 1]);
            return response()->json('کاربر مورد نظر فعال شد');
        } else {
            User::where('id', $id)->update(['active' => 0]);
            return response()->json('کاربر مورد نظر غیرفعال شد');
        }
    }

    //Rayat//active unit in unitsManage
    public function statusUnit(Request $request)
    {
        $id = $request->id;
        $active = Unit::where('id', $id)->value('active');
        if ($active == 0) {
            Unit::where('id', $id)->update(['active' => 1]);
            return response()->json('واحد مورد نظر فعال شد');
        } else {
            Unit::where('id', $id)->update(['active' => 0]);
            return response()->json('واحد مورد نظر غیرفعال شد');
        }
    }

    //Rayat//show edit unit in unitsManage
    public function unitsUpdateShow($id)
    {
        $unit = Unit::where('id', $id)->get();
        return view('admin.unitsUpdate', compact('unit'));
    }

    //Rayat//show edit unit in unitsManage
    public function usersUpdateShow($id)
    {
        $units = Unit::all();
        $user = User::where('id', $id)->get();
        return view('admin.usersUpdate', compact('user', 'units'));
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
                'unit_id' => $request->unit_id,
                'supervisor_id' => $request->supervisor_id,
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
        //dd($workers);
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

        $pageTitle="لیست کارت های کارگری";
        return view ('admin.exportedWorkersCard',compact('pageTitle'));

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
                    'card' => $fileName,
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
    public function productRequestManagement()
    {
        $pageTitle='مدیریت درخواست کالا';
        $pageName='productRequestManagement';
        $productRequests=Request2::where([['request_type_id',3],['active',0]])->get();
        foreach($productRequests as $productRequest)
        {
            $productRequest->request_record_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null]])->count();
            $productRequest->request_record_count_refused=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null]])->count();
        }

        return view ('admin.productRequestManagement', compact('pageTitle','productRequests','pageName'));
    }
    public function productRequestRecords($id)
    {
        $pageTitle='رکوردهای درخواست شماره '.$id;
        $requestRecords=RequestRecord::where([['request_id',$id],['price',0],['refuse_user_id',null]])->get();
        return view ('admin.productRequestRecords',compact('pageTitle','requestRecords'));
    }
    public function serviceRequestManagement()
    {
        $pageTitle='مدیریت درخواست خدمت';
        $serviceRequests=Request2::where([['request_type_id',2],['active',0]])->get();
        return view ('admin.serviceRequestManagement', compact('pageTitle','serviceRequests'));
    }
    public function refusedProductRequestManagementGet()
    {
        $pageTitle='درخواست های رد شده';
        $pageName='refusedProductRequestManagement';
        $productRequests=Request2::where([['request_type_id',3],['active',0],['refuse_record_count','!=',0]])->get();
        return view ('admin.productRequestManagement', compact('pageTitle','productRequests','pageName'));
    }
    public function acceptProductRequestManagementGet()
    {
        //change it later
        $pageTitle='درخواست های تایید شده';
        $pageName='acceptProductRequestManagement';
        $productRequests=Request2::where([['request_type_id',3],['active',0],['refuse_record_count','!=',0]])->get();
        foreach($productRequests as $productRequest)
        {
            $productRequest->request_record_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id',null]])->count();
            $productRequest->request_record_count_refused=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null]])->count();
        }
        return view ('admin.productRequestManagement', compact('pageTitle','productRequests','pageName'));

    }

}
