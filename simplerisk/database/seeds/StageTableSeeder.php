<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class StageTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stages')->insert([
            'id' => 6,
            'name' => 'General',
            'description' => 'Can occur during any thinkable stage',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('stages')->insert([
            'id' => 7,
            'name' => 'Take-off',
            'description' => 'For risks that can occur during Take-off',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('stages')->insert([
            'id' => 8,
            'name' => 'Cruising',
            'description' => 'In flight',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}