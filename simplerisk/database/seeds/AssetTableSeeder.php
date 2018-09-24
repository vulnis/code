<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class AssetTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assets')->insert([
            'id' => 1,
            'name' => 'Parrot AR.Drone',
            'value' => 1,
            'location' => 0,
            'team' => 0,
            'category_id' => 21,
            'description' => 'The parrot AR.Drone is a remotely controlled quadrocopter originally designed for augmented reality video games. Meanwhile, the AR.Drone is commonly used as a research platform. Apart from research institutions, the AR.Drone was also used during the “occupy wall street” actions to realise a robust police reconnaissance system',
            'created' => Carbon::now(),
        ]);
        DB::table('assets')->insert([
            'id' => 4,
            'name' => 'MQ-1 Predator',
            'value' => 1,
            'location' => 0,
            'team' => 0,
            'category_id' => 21,
            'created' => Carbon::now(),
        ]);
        DB::table('assets')->insert([
            'id' => 2,
            'name' => 'MQ-9-Reaper',
            'value' => 1,
            'location' => 0,
            'team' => 0,
            'category_id' => 21,
            'description' => 'The General Atomics MQ-9 Reaper is a remotely controlled UAV. It is the successor of the MQ-1 Predator. It uses the TCDL satellite communication system (SATCOM) as well as a direct LOS C-band communication.',
            'created' => Carbon::now(),
        ]);
        DB::table('assets')->insert([
            'id' => 3,
            'name' => 'RQ-170 Sentinel',
            'value' => 1,
            'location' => 0,
            'team' => 0,
            'category_id' => 21,
            'created' => Carbon::now(),
        ]);
    }
}