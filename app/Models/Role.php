<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RolePermission;

class Role extends BaseModel
{
    use HasFactory;

    protected $table = "roles";
    protected $fillable = ['name','slug'];
    protected $appends = ['e_id'];

    public $nonDeleteableRoles = [1,2,3];

    const LIST_PERMISSION = 'list-role';
    const VIEW_PERMISSION = 'view-role';
    const ADD_PERMISSION = 'add-role';
    const EDIT_PERMISSION = 'edit-role';
    const DELETE_PERMISSION = 'delete-role';

    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const CUSTOMER = 3;


    public function permissions() {
        return $this->belongsToMany('App\Models\Permission','role_permission');
    }
    
    public function users() {
        return $this->hasMany('App\Models\User','role_id');
    }

    //Accessors and Mutators
    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function setPermissions($permissions=[]){
        RolePermission::where('role_id',$this->id)->delete();
        foreach($permissions as $permission_id){
            RolePermission::create(['role_id'=>$this->id, 'permission_id'=>$permission_id]);
        }
    }

    static public function canOpenList(){
        return ( auth()->user()->can(self::LIST_PERMISSION) );
    }

    static public function canAdd(){
        return ( auth()->user()->can(self::ADD_PERMISSION) );
    }

    static public function canEdit(){
        return ( auth()->user()->can(self::EDIT_PERMISSION) );
    }

    public function canDelete(){
        return ( auth()->user()->can(self::DELETE_PERMISSION) && !in_array($this->id, $this->nonDeleteableRoles) );
    }
    
    public function getEditBtnHtml(){
        if( self::canEdit() ){
            $link = route('admin.roles.edit',[ 'id' => $this->e_id ]);
            return '<a href="'.$link.'" class="btn btn-primary btn-sm"><i class="icon icon-edit pr-0"></i> Edit </a>';
        }
    }

    public function getDeleteBtnHtml(){
        if( $this->canDelete() ) {
            $link = route('admin.roles.destroy',[ 'id' => $this->e_id ]);
            return '<a href="'.$link.'" class="btn btn-danger btn-sm"><i class="icon icon-trash pr-0"></i> Remove </a>';
        }
        return '';
    }
    
    //Static Functions
    static public function getPortalRoles($notIn=[]){
        $notIn = array_merge([1],$notIn);
        return self::whereNotIn('id',$notIn)->get();
    }

}
