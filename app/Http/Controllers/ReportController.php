<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Report;

use Illuminate\Support\Facades\Validator;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ReportController extends Controller
{
 
    public function show_all_reports_warehouse(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:'.date('Y')
        ]);
        if ($validator->fails())
            return response()->json([
                'status' => 0,
                'message' => 'inputs failed',
            ]);
        $report=Report::where('warehouse_id',$user->id)
                        ->where('month',$request->month)
                        ->where('year',$request->year)
                        ->get();
    return response()->json(
        [
            'status'=>1,
            'message'=>'report showing successfully',
            'data'=>$report
        ]
    );
}


public function show_all_reports_pharmacy(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:'.date('Y')
        ]);
        if ($validator->fails())
            return response()->json([
                'status' => 0,
                'message' => 'inputs failed',
            ]);
        $report=Report::where('pharmacy_id',$user->id)
                        ->where('month',$request->month)
                        ->where('year',$request->year)
                        ->get();
    return response()->json(
        [
            'status'=>1,
            'message'=>'report showing successfully',
            'data'=>$report
        ]
    );
}
}
