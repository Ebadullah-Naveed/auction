<?php

namespace ActivityLogManagement\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Activity\ActivityLog;
use App\Models\Activity\ActivityTemplate;
use Helper;

class AdminActivityLogController extends Controller
{
    
    public $module_title = 'Activity Logs';
    public $listing_view = 'activity-logs-management::index';

    public function index(){

        //checking open list permission
        if( ! ActivityLog::canOpenList() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        $data['title'] = $this->module_title;
        $data['templates'] = ActivityTemplate::all();
        $data['listing_fetch_url'] = route('admin.activity.logs.fetch');
        $data['detail_fetch_url'] = route('admin.activity.logs.detail');
        return view( $this->listing_view , $data );
    }

    public function fetch(Request $request) {
        
        $query = $this->getModelCollection($request);

        return Datatables::of($query)
        ->addColumn('text', function($row){
            return $row->getText();
        })
        ->make(true);
    }

    private function getModelCollection($request, $with=[]){
        $with = array_merge(['user:id,name','template'], $with);

        $query = ActivityLog::where('activity_time','!=',null);

        if( ($request->type != null) && ($request->type != '') ){
            $query->where('activity_template_id',$request->type);
        }

        if( ($request->date_from != null) && ($request->date_to != '') && ($request->date_to != null) && ($request->date_to != '') ){
            $query->whereRaw('DATE(`activity_time`) BETWEEN "'.$request->date_from.'" AND "'.$request->date_to.'"');
        }

        $query = $query->with($with);

        return $query;
    }

    public function detail(Request $request){
        $id = $request->id;
        $log = ActivityLog::find($id);
        return [
            'status' => true,
            'data' => [
                'user_id' => $log->user_id,
                'activity_time' => $log->activity_time,
                'description' => $log->getText(),
                'json_params' => $log->json_params
            ]
        ];
    }

}
