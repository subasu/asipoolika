<?php

namespace App\Http\Controllers;

use App\Models\UnitCount;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function unit_count(Request $request)
    {
        if (!$request->ajax())
        {
            abort(403);
        }
        $unit_counts=UnitCount::where('active',1)->orderBy('level')->get();
        return response()->json(compact('unit_counts'));
    }
}
