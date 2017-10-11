<?php

namespace App\Http\Controllers;

use App\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function home()
    {
//        return view('layouts.panelLayout');
        $names=Test::all();
        foreach($names as $name)
        {
            $name->name=decrypt($name->name);
        }

        return view('welcome',compact('names'));
    }
    public function save(Request $request)
    {
        $q= \Illuminate\Support\Facades\DB::table('tests')->insert([
            'name'=>encrypt($request->name)
        ]);
        if($q)
            return redirect('/');
        else
            return 'no';
    }
}
