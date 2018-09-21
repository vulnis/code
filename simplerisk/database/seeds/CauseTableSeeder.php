<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class CauseTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('causes')->insert([
            'id' => 10,
            'description' => 'The pilot was not familiar with the area',
            'category_id' => 10,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('causes')->insert([
            'id' => 15,
            'description' => 'Battery was not charged',
            'category_id' => 10,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('causes')->insert([
            'id' => 16,
            'description' => 'Defect',
            'category_id' => 12,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('causes')->insert([
            'id' => 17,
            'description' => 'Documented distance to powerlines to close',
            'category_id' => 11,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('causes')->insert([
            'id' => 18,
            'description' => 'GPS stabilizer defect',
            'category_id' => 12,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}