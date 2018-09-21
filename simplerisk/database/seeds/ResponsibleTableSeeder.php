<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ResponsibleTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('team')->insert([
            'value' => 11,
            'name' => 'Trainer',
        ]);
        DB::table('team')->insert([
            'value' => 12,
            'name' => 'Pilot',
        ]);
        DB::table('team')->insert([
            'value' => 13,
            'name' => 'Flight Trainer',
        ]);
        DB::table('team')->insert([
            'value' => 14,
            'name' => 'Chief-Pilot',
        ]);
        DB::table('team')->insert([
            'value' => 15,
            'name' => 'Flight Engineer',
        ]);
        DB::table('team')->insert([
            'value' => 16,
            'name' => 'Quality Engineer',
        ]);
    }
}