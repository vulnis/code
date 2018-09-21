<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class CauseConsequenceSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cause_consequence')->insert([
            'id' => 1,
            'cause_id' => 10,
            'consequence_id' => 1,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 2,
            'cause_id' => 10,
            'consequence_id' => 2,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 3,
            'cause_id' => 10,
            'consequence_id' => 3,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 4,
            'cause_id' => 10,
            'consequence_id' => 4,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 15,
            'cause_id' => 15,
            'consequence_id' => 1,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 16,
            'cause_id' => 16,
            'consequence_id' => 7,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 17,
            'cause_id' => 17,
            'consequence_id' => 1,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 18,
            'cause_id' => 17,
            'consequence_id' => 2,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 19,
            'cause_id' => 17,
            'consequence_id' => 3,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 20,
            'cause_id' => 18,
            'consequence_id' => 2,
        ]);
        DB::table('cause_consequence')->insert([
            'id' => 21,
            'cause_id' => 18,
            'consequence_id' => 3,
        ]);
    }
}