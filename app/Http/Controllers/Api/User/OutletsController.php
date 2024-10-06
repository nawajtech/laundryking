<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Outlet;
use App\Models\Pincode;
use App\Models\Workstation;
use Illuminate\Http\Request;

class OutletsController extends Controller
{
    //outlets listing
    public function index()
    {
        $value = '';
        $outlet = Outlet::with('pincode')->where('is_active', 1)->get();
        $user_data = array();
        if ($outlet) {
            foreach ($outlet as $u) {
                $data = array();
                foreach ($u->pincode as $p) {
                    $value = $p->pincode;
                    $data[] = array(
                        'pincodes' => $value,
                    );
                }
                $user_data[] = array(
                    'id' => $u->id,
                    'outlet_name' => $u->outlet_name,
                    'workstation_id' => $u->workstation_id,
                    'outlet_address' => $u->outlet_address,
                    'outlet_phone' => $u->outlet_phone??'',
                    'outlet_latitude' => $u->outlet_latitude??'',
                    'outlet_longitude' => $u->outlet_longitude ??'',
                    'upi_qr_code' => $u->qr_code ?? '',
                    'google_map' => $u->google_map ?? '',
                    'google_reviews' => $u->google_reviews ?? '',
                    'pincodes' => $data,
                );
            }
        }
        return response()->json([
            'status' => 1,
            'message' => 'Outlets Details.',
            'response' => $user_data,
        ]);
    }

// outlets under pincode
    public function outlet(Request $request)
    {
        $address = Address::where('id', $request->address_id)->first();
        if ($address) {
            $pincode = Pincode::where('pincode', $address->pincode)->first();
            if ($pincode) {
                $outlets = Outlet::where('id', $pincode->outlet_id)->first();
                if ($outlets) {
                    return response()->json([
                        'status' => 1,
                        'message' => 'Service Available',
                    ]);
                } else {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Service is not available this Pincode',
                    ]);
                }} else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Service is not available this Pincode',
                ]);
            }} else {
            return response()->json([
                'status' => 0,
                'message' => 'Service is not available this Pincode',
            ]);
        }
    }

    //workstation under outlet
    public function workstation(Request $request)
    {
        $user_data = [];
        $outlet = Outlet::with('workstation')->where('id', $request->outlet_id)->first();
        if ($outlet) {
            $user_data[] = array(
                'outlet_id' => $outlet->id,
                'outlet_name' => $outlet->outlet_name,
                'workstation_id' => $outlet->workstation->id ?? '',
                'workstation_name' => $outlet->workstation->workstation_name ?? '',
                'workstation_address' => $outlet->workstation->address ?? '',
                'workstation_contact' => $outlet->workstation->phone ?? '',
            );
            return response()->json([
                'status' => 1,
                'message' => 'Outlets Details.',
                'response' => $user_data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No data found',
            ]);
        }
    }
}
