<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class SeverityTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('impact')->insert([
            'value' => 1,
            'name' => 'Negligible',
            'description' => 'No injury to persons; Minor consequences on system',
        ]);
        DB::table('impact')->insert([
            'value' => 2,
            'name' => 'Minor',
            'description' => 'Minor incident to persons; Minor effect on system performance',
        ]);
        DB::table('impact')->insert([
            'value' => 3,
            'name' => 'Major',
            'description' => 'Injury to persons; Further operation not possible without major adjustments',
        ]);
        DB::table('impact')->insert([
            'value' => 4,
            'name' => 'Hazardous',
            'description' => 'Serious injury to persons; Major damage to equipment or buildings',
        ]);
        DB::table('impact')->insert([
            'value' => 5,
            'name' => 'Catastrophic',
            'description' => 'Death to people; Asset, equipment or buildings destroyed',
        ]);
    }
}