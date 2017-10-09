<?php

namespace App\Http\Controllers;

use App\Models\RequestRecord;
use App\Models\UnitCount;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //Kianfar : load uni list by ajax
    public function unit_count(Request $request)
    {
        if (!$request->ajax())
        {
            abort(403);
        }
        $unit_counts=UnitCount::where('active',1)->orderBy('level')->get();
        return response()->json(compact('unit_counts'));
    }
    //rayat
    public function save_img(Request $request)
    {
        if ($request->img) {
            $file = $request->img;
            $destinationPath = public_path();
            $file->move($destinationPath, $request->img->getClientOriginalName());
            $path = $destinationPath . '\\' . $request->img->getClientOriginalName();
            $image = file_get_contents($path);
            File::delete($path);
            $fileName = base64_encode($image);
            DB::table('signatures')->insert(
                [
                    'signature' => $fileName,
                    'user_id' => '3',
                    'priority' => '3',
                    'title' => '3',
                    'unit_id' => '3'
                ]
            );
            return view('user.test')->with('img', 'data:image/jpeg;base64,' . $fileName)->with('msg', 'ok');
        } else {
            return view('user.test')->with('msg', 'no file');

        }
    }
    public function ajaxPrice(Request $request)
    {
        $rate=$request->rate;
        $request_record_count=$request->record_count;
        $price=$rate*$request_record_count;
        return response()->json(compact('price'));
    }
    public function goToLoginPage()
    {
        if (Auth::check()) {
            $me=Auth::user();
            if($me->is_supervisor==1 and $me->unit_id!=3)
                $redirect='admin/productRequestManagement';
            elseif($me->is_supervisor==1 and $me->unit_id==3)
                $redirect='systemManager/signaturesList';
            else $redirect='user/productRequest';
            return redirect($redirect);
        } else
        return redirect('/login');
    }
}
