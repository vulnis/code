<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ProbabilityTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('likelihood')->insert([
            'value' => 1,
            'name' => 'Extremely improbable',
            'description' => 'Almost inconceivable that the event will occur ("Never happened")',
        ]);
        DB::table('likelihood')->insert([
            'value' => 2,
            'name' => 'Improbable',
            'description' => 'Very unlikely to occur, or not known to have occured ("It happened once and I heard about it from another operator")',
        ]);
        DB::table('likelihood')->insert([
            'value' => 3,
            'name' => 'Remote',
            'description' => 'Unlikely to occur, but possible or has occured rarely ("I know it from some events")',
        ]);
        DB::table('likelihood')->insert([
            'value' => 4,
            'name' => 'Occasional',
            'description' => 'Likely to occur sometimes or has occured infrequently ("Every second operation")',
        ]);
        DB::table('likelihood')->insert([
            'value' => 5,
            'name' => 'Frequently',
            'description' => 'Likely to occur many times or has occured frequently ("five times during operation")',
        ]);
    }
}