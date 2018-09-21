<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class SourceTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('source')->insert([
            'value' => 16,
            'name' => 'Battery',
            'type' => 'Cause',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('source')->insert([
            'value' => 17,
            'name' => 'GPS',
            'type' => 'Cause',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('source')->insert([
            'value' => 18,
            'name' => 'Transmitter',
            'type' => 'Cause',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('source')->insert([
            'value' => 19,
            'name' => 'Incident',
            'type' => 'Risk',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('source')->insert([
            'value' => 20,
            'name' => 'Brainstorm',
            'type' => 'Risk',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('source')->insert([
            'value' => 21,
            'name' => 'Survey',
            'type' => 'Risk',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}