<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'SuperAdmin',
            'slug' => 'superadmin',
        ]);
        
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'Customer',
            'slug' => 'customer',
        ]);

    }
}
