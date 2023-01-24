<?php

namespace RoleManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests\RoleFormRequest;
use Helper;

class AdminRoleController extends Controller
{

    public $module_title = 'Roles';
    public $listing_view = 'roles-management::index';
    public $add_edit_view = 'roles-management::add_edit';

    public function index(){

        // checking open list permission
        if( ! Role::canOpenList() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        $data['roles'] = Role::whereNotIn('id',[Role::CUSTOMER])->get();
        $data['add_url'] =  route('admin.roles.create');
        $data['title'] =  $this->module_title;
        return view( $this->listing_view ,$data);
    }

    private function getArrangedData($id=null) {
        $permissions = Permission::get();

        // arrange data for table view
        $permissionArray = [];
        foreach($permissions as $permission) {
            $permissionArray[$permission->slug] = [
                'id' => $permission->id,
                'name' => $permission->name,
            ];
        }

        $data['arranged_permission'] = $permissionArray;

        if($id){
            $data['title'] = 'Update Role';
            $data['role'] = Role::getByEid($id);         
            $data['action_url'] = route('admin.roles.update',['id'=>$data['role']->e_id]);         
        } else{
            $data['title'] = 'Add New Role';
            $data['role'] = null;
            $data['action_url'] = route('admin.roles.store');         
        }
        return $data;
    }

    public function create() {

        //checking create permission
        if( ! Role::canAdd() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        return view( $this->add_edit_view , $this->getArrangedData() );
    }

    public function store(RoleFormRequest $request) {
        try{
            $role = Role::create($request->input());
            $role->setPermissions($request['permissions']);

            Helper::successToast('Role has been added successfully');
            return redirect()->route('admin.roles');
        } catch(\Exception $e){
            Helper::errorToast();
            return back();
        }
    }

    public function edit($id) {

        //checking edit permission
        if( ! Role::canEdit() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        return view( $this->add_edit_view , $this->getArrangedData($id) );
    }

    public function update(RoleFormRequest $request,$id) {
        try{
            $role = Role::getByEid($id);
            $role->update($request->input());
            $role->setPermissions($request['permissions']);
            
            Helper::successToast('Role has been updated successfully');
            return redirect()->route('admin.roles');
        } catch(\Exception $e){
            Helper::errorToast();
            return back();
        }
    }

    public function destroy($id){
        try{
            $role = Role::getByEid($id);

            //checking delete permission
            if( ! $role->canDelete() ) {
                return Helper::redirectUnauthorizedPermission();
            }

            if( $role->users->count() > 0 ) {
                Helper::errorToast('Cannot be deleted, assigned to users.');
            } else {
                $role->setPermissions();
                $role->delete();
                Helper::successToast('Role has been deleted successfully');
            }
            return back();
        } catch(\Exception $e){
            Helper::errorToast();
            return back();
        }
    }

}
