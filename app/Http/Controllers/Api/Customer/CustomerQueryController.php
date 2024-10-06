<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerQuery;
use Illuminate\Http\Request;
use Validator;

class CustomerQueryController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $rules = [
            'phone' => 'required|min:10',
            'message' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else {
            CustomerQuery::create([
                'phone' => $request->phone,
                'message' => $request->message,
            ]);
            return response()->json([
                'status' => 1,
                'message' => "Your message Successfully Submitted",
            ]);
        }
    }
}
