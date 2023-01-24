<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends BaseModel
{
    use HasFactory;

    protected $table = "permissions";
    public $timestamps = false;

    //Permissions for those dont have models
    // const LIST_ADMIN_NOTIFICATION = 'admin-notifications';

    /**
     * New group creation mapping
     * permission array sequence must be 
     * 1. list-permission
     * 2. view-permission
     * 3. add-permission
     * 4. edit-permission
     * 5. delete-permission
     * 
     * if permission is not then empty string
     * if new permission added then add it in the PermissionSeeder also
     */
    static $groups = [
        'settings' => [
            'name' => 'Settings',
            'permissions' => [
                'list-settings',
                '',
                '',
                'edit-settings',
                ''
            ],
        ],
        'activity-logs' => [
            'name' => 'Activity Logs',
            'permissions' => [
                'list-activity-logs',
                '',
                '',
                '',
                ''
            ],
        ],
        'roles' => [
            'name' => 'Roles',
            'permissions' => [
                'list-role',
                'view-role',
                'add-role',
                'edit-role',
                'delete-role',
            ],
        ],
        'users' => [
            'name' => 'Admin User',
            'permissions' => [
                'list-admin-user',
                'view-admin-user',
                'add-admin-user',
                'edit-admin-user',
                'delete-admin-user',
            ],
        ],
        'customer-managment' => [
            'name' => 'Customer Management',
            'permissions' => [
                'list-customer',
                'view-customer',
                'add-customer',
                'edit-customer',
                'delete-customer'
            ],
        ],
        
    ];

    public function roles() {
        return $this->belongsToMany('App\Role','role_permission');
    }

    static public function getGroupData() {
        return self::$groups;
    }

    static public function canOpenAdminNotification() {
        return ( auth()->user()->can(self::LIST_ADMIN_NOTIFICATION) );
    }

}
