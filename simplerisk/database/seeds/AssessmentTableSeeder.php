<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class AssessmentTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sra')->insert([
            'id' => 10,
            'hazard_id' => 30,
            'cause_id' => 15,
            'probability_id' => 3,
            'severity_id' => 5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sra')->insert([
            'id' => 11,
            'hazard_id' => 31,
            'cause_id' => 18,
            'probability_id' => 4,
            'severity_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sra')->insert([
            'id' => 12,
            'hazard_id' => 32,
            'cause_id' => 16,
            'probability_id' => 3,
            'severity_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sra')->insert([
            'id' => 13,
            'hazard_id' => 33,
            'cause_id' => 10,
            'probability_id' => 2,
            'severity_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sra')->insert([
            'id' => 14,
            'hazard_id' => 33,
            'cause_id' => 17,
            'probability_id' => 4,
            'severity_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sra')->insert([
            'id' => 15,
            'hazard_id' => 33,
            'cause_id' => 18,
            'probability_id' => 1,
            'severity_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}