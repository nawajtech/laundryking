<?php

namespace App\Http\Middleware;
use Closure;
use App\Models\MasterSettings;
use Auth;

class StoreRestrictedTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $masterSetting = MasterSettings::where('master_title','store_start_time')->first();
        $start_time=$masterSetting->master_value;
        $masterSettings = MasterSettings::where('master_title','store_close_time')->first();
        $end_time=$masterSettings->master_value;
        date_default_timezone_set('Asia/Kolkata');
        // if not working hours, access forbidden
        if ((Auth::check()) && (Auth::user()->user_type==2) && !now()->isBetween($start_time, $end_time)) {
            return response()->json([
                'message' => 'Day is over, come back tomorrow'
            ], 403); // Status forbidden
        }
        return $next($request);
    }
}