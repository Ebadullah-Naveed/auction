<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ActivityTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // DB::table('activity_template')->truncate();
        DB::table('activity_template')->insert([
            'id' => 1,
            'activity_name' => 'user_login',
            'template' => '({user}) logged in.',
        ]);

        DB::table('activity_template')->insert([
            'id' => 2,
            'activity_name' => 'user_logout',
            'template' => '({user}) logged out.',
        ]);

    }
}
