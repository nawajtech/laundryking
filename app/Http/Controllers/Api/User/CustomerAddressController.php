<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Auth;
use Illuminate\Http\Request;
use Validator;

class CustomerAddressController extends Controller
{
    public function __construct()
    {
        $this->customer = Auth::guard('user')->user();
    }

    //insert address data
    public function registeraddress(Request $request)
    {
        $customer_id = $request->customer_id;
        $rules = [
            'pincode' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else {
            $address = Address::where('customer_id', $customer_id)->count();
        }

        if ($address >= 1) {
            Address::create([
                'customer_id' => $customer_id,
                'flat_number' => $request->flat_number,
                'area' => $request->area,
                'address' => $request->address,
                'route_suggestion' => $request->route_suggestion,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'pincode' => $request->pincode,
                'address_type' => $request->address_type,
                'address_name' => $request->address_name,
                'other' => $request->other,
                'status' => 0,
            ]);
            return response()->json([
                'status' => 1,
                'message' => "Your Address successfully Saved",

            ]);
        } else {
            Address::create([
                'customer_id' => $customer_id,
                'flat_number' => $request->flat_number,
                'area' => $request->area,
                'address' => $request->address,
                'route_suggestion' => $request->route_suggestion,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'pincode' => $request->pincode,
                'address_type' => $request->address_type,
                'address_name' => $request->address_name,
                'other' => $request->other,
                'status' => 1,
            ]);
            return response()->json([
                'status' => 1,
                'message' => "Your Address successfully Saved",

            ]);
        }
    }

    // Address data Update
    public function updateaddress(Request $request)
    {
        $rules = [
            'pincode' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else {
            $customer_id = $request->customer_id;
            $checkuser = Address::where('id', $request->id)->count();
            if ($checkuser > 0) {
                $data['flat_number'] = $request->flat_number;
                $data['area'] = $request->area;
                $data['address'] = $request->address;
                $data['route_suggestion'] = $request->route_suggestion;
                $data['latitude'] = $request->latitude;
                $data['longitude'] = $request->longitude;
                $data['address_name'] = $request->address_name;
                $data['address_type'] = $request->address_type;
                $data['pincode'] = $request->pincode;
                Address::where('id', $request->id)->update($data);
            }
            return response()->json([
                'status' => 1,
                'message' => 'Address updated successfully.',
            ]);
        }
    }

    // Address Data Delete
    public function deleteaddress(Request $request)
    {
        $customer_id = $request->customer_id;
        $checkuser = Address::where('id', $request->id)->delete();
        if ($checkuser) {
            return response()->json([
                'status' => 1,
                'message' => 'You have successfully removed your address',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Not found',
            ]);
        }
    }

    //User data view from  user_id
    public function driver_address(Request $request)
    {
        $address = Address::where('customer_id', $request->customer_id)->get();
        $data = [];
        if (!$address->isEmpty()) {
            foreach ($address as $u) {
                if ($u->address_type == 'other') {
                    $data[] = array(
                        'id' => $u->id,
                        'customer_id' => $u->customer_id,
                        'flat_number' => $u->flat_number ?? '',
                        'area' => $u->area ?? '',
                        'address' => $u->address ?? '',
                        'route_suggestion' => $u->route_suggestion ?? '',
                        'latitude' => $u->latitude ?? '',
                        'longitude' => $u->longitude ?? '',
                        'pincode' => $u->pincode ?? '',
                        'address_type' => $u->address_type ?? '',
                        'address_name' => $u->address_name ?? '',
                        'other' => $u->other ?? '',
                        'status' => $u->status,
                    );
                } else {
                    $data[] = array(
                        'id' => $u->id,
                        'customer_id' => $u->customer_id,
                        'flat_number' => $u->flat_number ?? '',
                        'area' => $u->area ?? '',
                        'address' => $u->address ?? '',
                        'route_suggestion' => $u->route_suggestion ?? '',
                        'latitude' => $u->latitude ?? '',
                        'longitude' => $u->longitude ?? '',
                        'pincode' => $u->pincode ?? '',
                        'address_type' => $u->address_type ?? '',
                        'address_name' => $u->address_name ?? '',
                        'other' => $u->other ?? '',
                        'status' => $u->status,
                    );
                }
            }
            return response()->json([
                'status' => 1,
                'message' => 'Address Details.',
                'response' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Data Found.',
            ]);
        }
    }

    //customer default address
    public function default_address(Request $request)
    {
        $customer_id = $request->customer_id;
        $checkuser = Address::where('customer_id', $customer_id)->where('status', 1)->first();
        if ($checkuser) {
            $data['status'] = 0;
            Address::where('id', $checkuser->id)->update($data);
            $data['status'] = 1;
            Address::where('id', $request->id)->update($data);
            return response()->json([
                'status' => 1,
                'message' => 'Selected address set as default address',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No data found',
            ]);
        }
    }

}
