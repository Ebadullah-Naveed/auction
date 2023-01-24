<?php

namespace UserManagement\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\UserFormRequest;
use Helper;

class AdminUserController extends Controller
{
    
    public $module_title = 'Admin Users';
    public $listing_view = 'user-management::index';
    public $add_edit_view = 'user-management::add_edit';
    public $show_view = 'user-management::show';

    public function index(){

        //checking edit permission
        if( ! User::canOpenList() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        $data['title'] = $this->module_title;
        $data['roles'] = Role::getPortalRoles();
        $data['add_url'] = route('admin.users.create');
        $data['listing_fetch_url'] = route('admin.users.fetch');
        return view( $this->listing_view , $data );
    }

    public function fetch(Request $request) {
        
        $query = $this->getModelCollection($request);

        return Datatables::of($query)
        ->addColumn('edit_btn', function($row){
            return $row->getEditBtnHtml();
        })
        ->addColumn('view_btn', function($row){
            return $row->getViewBtnHtml();
        })
        ->addColumn('status_html', function($row){
            return $row->getStatusHtml();
        })
        ->rawColumns(['edit_btn','view_btn','status_html'])
        ->make(true);
    }

    private function getModelCollection($request, $with=[]){
        $with = array_merge(['role'], $with);
        $query = User::where('role_id','<>',Role::SUPER_ADMIN);

        if( ($request->status != null) && ($request->status != '') ){
            $query->where('is_active',$request->status);
        }

        if( $request->role && ($request->role != '') ){
            $query->where('role_id',$request->role);
        }

        $query = $query->with($with);
        // ->select(['users.id as uid','users.*']);

        return $query;
    }

    private function getArrangedData($id=null) {
        $data['roles'] = Role::getPortalRoles();
        if($id){
            $data['title'] = 'Update Admin User';
            $data['user'] = User::getByEid($id);
            $data['action_url'] = route('admin.users.update',['id'=>$data['user']->e_id]);         
        } else{
            $data['title'] = 'Add New Admin User';
            $data['user'] = null;
            $data['action_url'] = route('admin.users.store');         
        }
        return $data;
    }

    public function create(){

        if( ! User::canAdd() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        return view( $this->add_edit_view , $this->getArrangedData() );
    }

    public function store(UserFormRequest $request){
        try{
            $user = User::create($request->input());
            Helper::successToast('User has been added successfully');
            return redirect()->route('admin.users');
        } catch(\Exception $e){
            Helper::errorToast($e);
            return back();
        }
    }

    public function edit($id){

        //checking edit permission
        if( ! User::canEdit() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        return view( $this->add_edit_view , $this->getArrangedData($id) );
    }

    public function update(UserFormRequest $request,$id){
        try{
            $role = User::getByEid($id);
            $role->update($request->input());
            Helper::successToast('User has been updated successfully');
            return redirect()->route('admin.users');
        } catch(\Exception $e){
            Helper::errorToast();
            return back();
        }
    }

    public function show($id) {
        $data['user'] = User::getByEid($id);
        $data['title'] = 'User Details';
        $data['action_url'] = route('admin.users');
        return view($this->show_view, $data);
    }

}
