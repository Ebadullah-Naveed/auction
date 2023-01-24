<?php

namespace SettingManagement\Http\Controllers;

use Illuminate\Http\Request;
use Helper;
use App\Models\Setting;

class AdminSettingController extends Controller
{
    public $module_title = 'Settings';
    public $add_edit_view = 'setting-management::edit';

    public function edit(){

        //checking open list permission
        if( ! Setting::canOpenList() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        $data['settings'] = Setting::all();
        $data['title'] =  $this->module_title;
        $data['action_url'] =  route('admin.settings.update');
        return view( $this->add_edit_view , $data);
    }

    public function update(Request $request){

        //checking edit permission
        if( ! Setting::canEdit() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        try{
            unset($request['_token']);
            foreach( $request->input() as $k=>$v ){
                if( is_array($v) ){
                    $v = implode(',',$v);
                }
                Setting::where('key','like', $k)->update(['value' => $v]);
            }
            Helper::successToast('Updated Succesfully');
            return back();
        } catch(\Exception $e){
            Helper::errorToast();
            return back();
        }

    }

    public function cronjobListing(){
        $data['title'] = 'Cron Jobs';
        return view('setting-management::cronjobs.index',$data);
    }

}
