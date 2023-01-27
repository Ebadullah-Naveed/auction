<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //test
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin',
            'username' => 'superadmin',
            'email' => 'admin@admin.com',
            'phone_number' => '03000000001',
            'role_id' => '1',
            // Admin@123
            'password' => '$2y$10$Fgw2mcZUwMDuYgkDuwxuM.z6Vr7ZxhdhE0QQk1tXFtbs9yqQG75bC',
            'image' => 'profile_pictures/avatar.png',
            'status' => 1
        ]);
    }
}
