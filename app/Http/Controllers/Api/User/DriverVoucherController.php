<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\Slide;
use App\Models\ServiceCategory;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DriverVoucherController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = Auth::guard('user')->user();
    }

    public function voucher(Request $request)
    {
        $voucher = Voucher::where('code', $request->voucher_code)->first();
        $now = Carbon::now();
        $curdatetime = $now->format('Y-m-d H:i:s');
        $valid_coupon = Voucher::where('code', $request->voucher_code)->where('is_active', '=', 1)->where('valid_from', '<', $curdatetime)->where('valid_to', '>', $curdatetime)->first();
        if (!$voucher) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid coupon code. Please try again.',
            ]);
        } elseif (!$valid_coupon) {
            return response()->json([
                'status' => 0,
                'message' => 'Coupon Expired',
            ]);
        } else {
            $voucher_used = Order::where('customer_id', $request->customer_id)->where('voucher_id', $voucher->id)->count();
            $each_user_useable = $voucher->each_user_useable;
            if ($voucher_used >= $each_user_useable) {
                return response()->json([
                    'status' => 0,
                    'message' => 'You have already used this coupon',
                ]);               
            } elseif ($voucher->total_used >= $voucher->total_useable) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Coupon codes no longer exists',
                ]);
            } elseif ($voucher->is_deleted == 1) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Coupon codes no longer exists',
                ]);
            }
            $data[] = array(
                'voucher_id' => $voucher->id,
                'voucher_code' => $voucher->code,
                'voucher_to' => $voucher->valid_to,
                'voucher_from' => $voucher->valid_from,
            );
            return response()->json([
                'status' => 1,
                'message' => 'Coupon has been applied',
                'data' => $data,
            ]);
        }
    }
}
