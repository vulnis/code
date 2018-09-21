<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class LevelTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('risk_levels')->insert([
            'value' => 7.0,
            'name' => 'High',
            'color' => 'orangered',
            'display_name' => 'High',
        ]);
        DB::table('risk_levels')->insert([
            'value' => 4.0,
            'name' => 'Medium',
            'color' => 'orange',
            'display_name' => 'Medium',
        ]);
        DB::table('risk_levels')->insert([
            'value' => 0.0,
            'name' => 'Low',
            'color' => '#fff942',
            'display_name' => 'Low',
        ]);
        DB::table('risk_levels')->insert([
            'value' => 10.1,
            'name' => 'Veryhigh',
            'color' => 'red',
            'display_name' => 'Very High',
        ]);
    }
}