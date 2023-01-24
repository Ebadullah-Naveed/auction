<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();
        DB::table('settings')->insert([
            'key' => 'version',
            'value' => '1.0',
            'title' => 'Admin Portal Version',
            'description' => ''
        ]);
        
        
        DB::table('settings')->insert([
            'key' => 'maintenance_mode',
            'value' => '1',
            'title' => 'Maintenance Mode',
            'description' => '',
            'json_params' => '{"type":"dropdown","data":[{"value":1,"label":"Enable"},{"value":0,"label":"Disable"}]}'
        ]);


    }
}
