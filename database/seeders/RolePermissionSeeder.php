<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_permission')->truncate();
        DB::table('role_permission')->insert([

            //Settings
            ['role_id'=>1, 'permission_id'=>1],
            ['role_id'=>1, 'permission_id'=>2],

            //Role
            ['role_id'=>1, 'permission_id'=>3],
            ['role_id'=>1, 'permission_id'=>4],
            ['role_id'=>1, 'permission_id'=>5],
            ['role_id'=>1, 'permission_id'=>6],
            ['role_id'=>1, 'permission_id'=>7],

            
        ]);
    }
}
