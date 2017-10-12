<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Models\Unit;
use App\Models\UnitCount;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class SystemManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //Kianfar : return signature list view
    public function getSignatures()
    {
        $pageTitle="مدیریت امضاها";
        $signatures=Signature::all();
        return view('system_manager.signature',compact('signatures','pageTitle'));
    }
    //Kianfar : return add new signature view
    public function getAddSignature()
    {
        $pageTitle="درج امضای جدید";
        $units=Unit::where('active',1)->get();
        return view('system_manager.add_signature',compact('units','pageTitle'));
    }

    public function unit_user_list(Request $request)
    {
        if (!$request->ajax())
        {
            abort(403);
        }

        $users =User::where('unit_id',$request->unit_id)->get();
        return response()->json(compact('users'));
    }
    public function getEditSignature($id)
    {
        $signature_info=Signature::where('id',$id)->get();
        $units=Unit::where('active',1)->get();
//        dd($signature_info,$units);
        return view('system_manager.edit_signature',compact('signature_info','units'));
    }

    //shiri : below function is to add new signature....
    public function addSignature(Request $request)
    {
        $checkSignatureExistence = Signature::where('user_id',$request->userId)->get();
        if(count($checkSignatureExistence) == 0)
        {
            if(!$request->ajax())
            {
                abort(403);
            }else
            {
                $extension = $request->file->getClientOriginalExtension();
                $fileSize  = $request->file->getClientSize();
              //  dd($fileSize);
                if($fileSize < 150000)
                {
                    if($extension == 'png' || $extension == 'PNG')
                    {
                        $file = $request->file;
                        $file->move(public_path(), $request->file->getClientOriginalName());
                        $path = public_path() . '\\' . $request->file->getClientOriginalName();
                        $file = file_get_contents($path);
                        File::delete($path);
                        $fileName = base64_encode($file);

                        $query = DB::table('signatures')->insert
                        ([
                            'forced'     => $request->forced,
                            'signature'  => $fileName,
                            'user_id'    => $request->userId,
                            'unit_id'    => $request->unitId,
                            'created_at'  =>Carbon::now(new \DateTimeZone('Asia/Tehran'))
                        ]);
                        if($query)
                        {
                            return response('اطلاعات  با موفقیت ثبت گردید');
                        }
                    }else
                    {
                        return response('پسوند فایل امضا باید از نوع png باشد');
                    }
                }else
                    {
                        return response('حجم فایل امضا نباید بیش از 1مگابایت باشد');
                    }


            }
        }else
            {
                return response('امضای مدیر این واحد قبلا ثبت شده است');
            }

    }

    //shiri : below function is related to show signature in it's own page
    public function showSignature($id)
    {
        $pageTitle = 'نمایش امضاء';
        $signatures = Signature::where('id',$id)->get();
        foreach ($signatures as $signature)
        {
            $signature->signature = 'data:image/jpeg;base64,'.$signature->signature;
        }
        return view('system_manager.showSignature',compact('signatures','pageTitle'));
    }

    //shiri : below function is to change signature status to forced
    public function makeSignatureForced(Request $request)
    {

        $query = Signature::where('id',$request->signatureId)->update
        ([
            'forced'      => 1,
            'updated_at'  => Carbon::now(new \DateTimeZone('Asia/Tehran'))
        ]);
        if($query)
        {
            return response('وضعیت امضاء  از اختیاری به اجباری تغییر یافت');
        }
    }

    //shiri : below function is change signature status to unforced
    public function makeSignatureUnforced(Request $request)
    {
        $query = Signature::where('id',$request->signatureId)->update
        ([
            'forced'     => 0,
            'updated_at' => Carbon::now(new \DateTimeZone('Asia/Tehran'))
        ]);

    }

    //
    public function signaturesList()
    {
        $pageTitle = 'لیست امضاها';
        $signatures = Signature::all();
//        dd($signatures);
        return view('system_manager.signature',compact('signatures','pageTitle'));
    }

    public function access_levelGet()
    {
        $pageTitle='تعیین سطح دسترسی';
//        return view('system_manager.access_level',compact('pageTitle'));
        return view('comingSoon',compact('pageTitle'));
    }
}
