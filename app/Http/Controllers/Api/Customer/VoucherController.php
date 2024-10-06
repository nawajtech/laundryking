<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\Slide;
use App\Models\ServiceCategory;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }

    public function index()
    {
        $category = array();
        $data1 = array();
        $data = array();
        $categories = ServiceCategory::where('is_active', '=', 1)->get();
        if ($categories) {
            foreach ($categories as $c) {
                $file = asset('/uploads/category/' . $c->image);
                $category[] = array(
                    'category_id' => $c->id,
                    'category_name' => $c->service_category_name,
                    'image' => $file,
                );
            }
        }
        $slide=[];
        $slides = Slide::where('is_active', '=', 1)->get();
        if ($slides) {
            foreach ($slides as $s) {
                $file2 = asset('/uploads/slide/' . $s->image);
                $slide[] = array(
                    'slide_id' => $s->id,
                    'slide_title' => $s->title,
                    'slide_image' => $file2,
                );
            }
        }

        $data=[];
        $now = Carbon::now();
        $curdatetime = $now->format('Y-m-d H:i:s');
        $voucher = Voucher::where('is_active', '=', 1)->where('valid_from', '<=', $curdatetime)->where('valid_to', '>=', $curdatetime)->get();
        // dd($voucher);
        if (count($voucher)>0) {
            foreach ($voucher as $s) {
                $discount_type = '';
                if ($s && $s->discount_type == 1) {
                    $discount_type = "Percentage";
                } elseif ($s && $s->discount_type == 2) {
                    $discount_type = "Flat";
                }
                $file = asset('/uploads/voucher/' . $s->image);
                $data[] = array(
                    'voucher_id' => $s->id,
                    'voucher_code' => $s->code,
                    'no_of_users' => $s->no_of_users,
                    'each_user_useable' => $s->each_user_useable,
                    'total_useable' => $s->total_useable,
                    'total_used' => $s->total_used,
                    'discount_type' => $discount_type,
                    'discount_amount' => number_format($s->discount_amount ,2),
                    'cutoff_amount' => number_format($s->cutoff_amount ,2),
                    'voucher_to' => date('Y-m-d ', strtotime($s->valid_to)),
                    'voucher_from' => date('Y-m-d ', strtotime($s->valid_from)),
                    'details' => $s->details,
                    'image' => $file,
                );
            }
        }
        if($categories OR $slides  OR count($voucher)>0){
            return response()->json([
                'status' => 1,
                'message' => 'Voucher Category and Promotion Details.',
                'voucher' => $data,
                'category' => $category,
                'slide' => $slide,
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'No data found.',
            ]);
        }
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
            $voucher_used = Order::where('customer_id', $this->customer->id)->where('voucher_id', $voucher->id)->count();
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
