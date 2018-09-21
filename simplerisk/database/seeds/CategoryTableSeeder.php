<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class CategoryTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert([
            'value' => 10,
            'name' => 'Human Factors',
            'type' => 'Cause',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category')->insert([
            'value' => 11,
            'name' => 'Documentation',
            'type' => 'Cause',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category')->insert([
            'value' => 12,
            'name' => 'Technical',
            'type' => 'Cause',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category')->insert([
            'value' => 18,
            'name' => 'Technical',
            'type' => 'Risk',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category')->insert([
            'value' => 19,
            'name' => 'Pilot/People',
            'type' => 'Risk',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category')->insert([
            'value' => 20,
            'name' => 'Active failure',
            'type' => 'Risk',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}