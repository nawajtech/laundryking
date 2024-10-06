<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        //
    }

    //Search customer by phone name and email
    public function search(Request $request)
    {
        $data = array();
        if($request->name){
        $customer_data = Customer::where(function($query) use ($request) { 
            $query->where('phone', 'like',  $request->name )->orWhere('email', 'like', $request->name );
        })->first();
        if($customer_data) {
            if ($customer_data->image) {
                $image = asset('uploads/customer/' . $customer_data->image);
            } elseif($customer_data->image == NULL) {
                $image = asset('uploads/customer/avatar-370-456322.png');
            }
        $data[] = array(
            'customer_id' => $customer_data->id,
            'customer_name' => $customer_data->name,
            'phone_number' => $customer_data->phone,
            'email' => $customer_data->email ?? '',
            'image' => $image,
        );
        return response()->json([
            'status' => 1,
            'response' => $data,
        ]);
    } 
    } else{
        return response()->json([
            'status' => 0,
            'message' => 'Record not found',
        ]);
    }
    }
}