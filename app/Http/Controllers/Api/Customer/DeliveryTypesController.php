<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\DeliveryType;

class DeliveryTypesController extends Controller
{
    public function index()
    {
        $delivery = DeliveryType::where('is_active', '=', 1)->get();
        $data=array();
        if ($delivery) {
            foreach ($delivery as $s) {
                $data[] = array(
                    'id' => $s->id,
                    'delivery_name' => $s->delivery_name,
                    'type' => $s->type,
                    'amount' => number_format($s->amount ,2),
                    'cut_of_amount' => number_format($s->cut_off_amount ,2),
                    'cut_of_charge' => number_format($s->cut_off_charge ,2),
                    'delivery_in_days' => $s->delivery_in_days,
                    'delivery_time' => date('h:i', strtotime($s->delivery_time_from)) .'am'  .' - ' .date('h:i', strtotime($s->delivery_time_to)) .'pm',
                    'pickup_time' => date('h:i', strtotime($s->pickup_time_from)) .'am'  .' - ' .date('h:i', strtotime($s->pickup_time_to)) .'pm',
                    
                );
            }
            return response()->json([
                'status' => 1,
                'message' => 'Delivery Details.',
                'response' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Data not found',
            ]);
        }

    }
}
