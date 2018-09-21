<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ConsequenceTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('consequences')->insert([
            'id' => 1,
            'name' => 'Harm to people',
            'description' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('consequences')->insert([
            'id' => 2,
            'name' => 'Damage to asset',
            'description' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('consequences')->insert([
            'id' => 3,
            'name' => 'Damage to infrastructure',
            'description' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('consequences')->insert([
            'id' => 4,
            'name' => 'Financial loss',
            'description' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('consequences')->insert([
            'id' => 7,
            'name' => 'No stream',
            'description' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}