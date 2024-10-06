<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Helper\CommonHelper;
use App\Models\MasterSettings;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->user = Auth::guard('user')->user();
    }

    //Profile details
    public function index(Request $request)
    {
        $user = $this->user;
        $checkuser = User::where('id', $this->user->id)->where('is_active', '1')->first();
        if ($checkuser) {
            $master_setting = MasterSettings::where('master_title', 'default_phone_number')->where('is_active', 1)->first();
            $default_number = $master_setting->master_value;
            $master_setting_email = MasterSettings::where('master_title', 'store_email')->where('is_active', 1)->first();
            $default_email = $master_setting_email->master_value;
            $file = 'uploads/user/' . $user->image;
            $image = CommonHelper::file_exists_path($file);
            $user_data = array(
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $image,
                'default_number' => $default_number,
                'default_email' => $default_email,
            );
            return response()->json([
                'status' => 1,
                'message' => 'Profile Details.',
                'response' => $user_data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Data Found.',
            ]);
        }
    }

    // Customer profile update
    public function update(Request $request)
    {   
        $rules = [
        'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        }
        $image = '';
        if ($request->has('image')) {
            $path = 'user';
            $image = CommonHelper::imageupload($request->file('image'), $path);
        } else {
            $user = $this->user;
            $checkuser = User::where('id', $this->user->id)->where('is_active', '1')->first();
            $image =$checkuser ->image;
        }
        $data['name'] = $request->name;
        $data['user_type'] = $request->user_type;
        $data['image'] = $image;
        User::where('id', $this->user->id)->update($data);
        return response()->json([
            'status' => 1,
            'message' => 'Profile updated successfully.',
        ]);
    }

    //Customer profile delete
    public function delete()
    {
        $user_id = Auth::user()->id;
        $checkuser = User::where('id', $this->user->id)->where('is_active', '1')->first();
        if ($checkuser) {
            $data['is_active'] = 0;
            User::where('id', $this->user->id)->update($data);
            return response()->json([
                'status' => 1,
                'message' => 'Deleted Successfully',
            ]);
        }
    }
}
