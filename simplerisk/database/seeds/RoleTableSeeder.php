<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            'value' => 3,
            'name' => 'Batch'
        ]);
        DB::table('role')->insert([
            'value' => 2,
            'name' => 'User'
        ]);
        DB::table('role')->insert([
            'value' => 1,
            'name' => 'Administrator'
        ]);
    }
}