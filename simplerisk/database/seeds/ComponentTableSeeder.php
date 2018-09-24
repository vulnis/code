<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ComponentTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('component')->insert([
            'id' => 1,
            'name' => 'UAV',
            'category_id' => 21,
        ]);
        DB::table('component')->insert([
            'id' => 2,
            'name' => 'Ground Control Station',
            'category_id' => 21,
        ]);
        DB::table('component')->insert([
            'id' => 3,
            'name' => 'Operations',
            'parent_id' => 2,
            'category_id' => 21,
        ]);
        DB::table('component')->insert([
            'id' => 4,
            'name' => 'Communication Link',
            'parent_id' => 2,
            'category_id' => 21,
        ]);
        DB::table('component')->insert([
            'id' => 5,
            'name' => 'Communication Link',
            'description' => 'The in-flight communication is always wireless and may be divided into two types: a) direct, line-of-sight (LOS) communication and b) indirect – mostly – satellite communication (SATCOM).',
            'parent_id' => 1,
            'category_id' => 21,
        ]);
        DB::table('component')->insert([
            'id' => 6,
            'name' => 'Base System',
            'description' => 'The foundation of the UAV linking together the UAV components. It is needed to allow inter-component communication and controls the sensor, navigation, avionic and communication system. It may be considered as an UAV “operating system”. The base system also allows the integration of further optional components such as special sensors or payload systems.',
            'parent_id' => 1,
            'category_id' => 21,
        ]);
        DB::table('component')->insert([
            'id' => 7,
            'name' => 'Sensors',
            'description' => 'The sensor system consists of the sensory equipment of the UAV together with integrated pre-processing functionalities. For common military UAVs these sensors are often cameras with different capabilities. UAVs may be equipped with further sensors, such as INS, GPS and radar.',
            'parent_id' => 1,
            'category_id' => 21,
        ]);
        DB::table('component')->insert([
            'id' => 8,
            'name' => 'Avionics',
            'description' => 'The avionic system is responsible for the conversion of received control commands to commands of the engine, flaps, rudder, stabilisers and spoilers.',
            'parent_id' => 1,
            'category_id' => 21,
        ]);
        DB::table('component')->insert([
            'id' => 9,
            'name' => 'Payload',
            'description' => 'Objects that can be attached to the UAV not being Sensors, enhancing Avionics or serving Communication',
            'parent_id' => 1,
            'category_id' => 21,
        ]);
        DB::table('component')->insert([
            'id' => 10,
            'name' => 'Autonomous Control',
            'parent_id' => 1,
            'category_id' => 21,
        ]);
    }
}