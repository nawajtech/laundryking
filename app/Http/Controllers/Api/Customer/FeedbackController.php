<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Auth;
use Illuminate\Http\Request;
use Validator;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }

    public function insert(Request $request)
    {
        $rules = [
            'rating' => 'required',
            'feedback' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else {
            $data['rating'] = $request->rating;
            $data['feedback'] = $request->feedback;
            $ord = Order::where('customer_id', $this->customer->id)->where('id', $request->order_id)->update($data);
            if($ord){
            return response()->json([
                'status' => 1,
                'message' => "Thank you for your valuable feedback",
            ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => "Please check once",
                ]);
            }
        }
    }
}
