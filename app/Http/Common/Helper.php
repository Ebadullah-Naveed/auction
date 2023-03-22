<?php

namespace App\Http\Common;

use Illuminate\Http\Request;
use Session;
use App\Models\Activity\ActivityLog;
use App\Models\Setting;
use DB;
use Carbon\Carbon;
use Response;
use App\Events\GeneralEvent;

class Helper {

    /**
     * Activity Logs Constants
     */
    const LOG_USER_LOGIN = 1;
    const LOG_USER_LOGOUT = 2;

    public static function toast($type,$message,$title=' ') {
        Session::flash('title',$title);
        Session::flash('type',$type);
        Session::flash('message',$message);
    }

    public static function successToast($message,$title=' ') {
        self::toast('success',$message,$title);
    }

    public static function errorToast($message='Something went wrong, Please try again.',$title=' ') {
        self::toast('error',$message,$title);
    }

    public static function createActivityLog($template_id,$params=[]){
        try{
            ActivityLog::create([
                'user_id' => (auth()->check())?auth()->user()->id:1,
                'activity_template_id' => $template_id,
                'activity_time' => now(),
                'json_params' => json_encode($params),
            ]);
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    public static function redirectUnauthorizedPermission() {
        self::errorToast('You are not authorized to access this page.');
        return redirect()->route('home');
    }

    function generalEvent($data)
    {
        try {
            $data = json_encode($data);
            GeneralEvent::dispatch($data);
            return $data;
        } catch (Exception $e) {
            //return nothing
            \Log::alert("Something went wrong! General-Event "."File: ".$e->getFile().' '.$e->getLine());
        }
    }
}