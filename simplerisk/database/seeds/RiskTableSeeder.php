<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class RiskTableSeeder extends Seeder
{
    /**
     * Populate the role table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('risks')->insert([
            'id' => 30,
            'status' => 'New',
            'subject' => 'Lack of power',
            'reference_id' => '',
            'location' => 0,
            'source' => 20,
            'category' => 18,
            'team' => 0,
            'technology' => 0,
            'owner' => 0,
            'manager' => 0,
            'assessment' => '',
            'notes' => '',
            'submission_date' => Carbon::now(),
            'last_update' => Carbon::now(),
            'mitigation_id' => 0,
            'mgmt_review' => 0,
            'project_id' => 0,
            'submitted_by' => 2,
            'additional_stakeholders' => ''
        ]);
        DB::table('risks')->insert([
            'id' => 31,
            'status' => 'New',
            'subject' => 'Under-shooting or overrunning during take-off',
            'reference_id' => '',
            'location' => 0,
            'source' => 20,
            'category' => 19,
            'team' => 0,
            'technology' => 0,
            'owner' => 0,
            'manager' => 0,
            'assessment' => '',
            'notes' => '',
            'submission_date' => Carbon::now(),
            'last_update' => Carbon::now(),
            'mitigation_id' => 0,
            'mgmt_review' => 0,
            'project_id' => 0,
            'submitted_by' => 2,
            'additional_stakeholders' => ''
        ]);
        DB::table('risks')->insert([
            'id' => 32,
            'status' => 'New',
            'subject' => 'Camera failure',
            'reference_id' => '',
            'location' => 0,
            'source' => 20,
            'category' => 18,
            'team' => 0,
            'technology' => 0,
            'owner' => 0,
            'manager' => 0,
            'assessment' => '',
            'notes' => '',
            'submission_date' => Carbon::now(),
            'last_update' => Carbon::now(),
            'mitigation_id' => 0,
            'mgmt_review' => 0,
            'project_id' => 0,
            'submitted_by' => 2,
            'additional_stakeholders' => ''
        ]);
        DB::table('risks')->insert([
            'id' => 33,
            'status' => 'New',
            'subject' => 'Near miss of powerline',
            'reference_id' => '',
            'location' => 0,
            'source' => 19,
            'category' => 20,
            'team' => 0,
            'technology' => 0,
            'owner' => 0,
            'manager' => 0,
            'assessment' => '',
            'notes' => 'During the The DII-11 operation the vehicle, caused by upcoming wind, had almost contact with a power line. The pilot who was in visual contact with the drone was able to avoid the contact by manoevring it out of the danger zone.',
            'submission_date' => Carbon::now(),
            'last_update' => Carbon::now(),
            'mitigation_id' => 0,
            'mgmt_review' => 0,
            'project_id' => 0,
            'submitted_by' => 2,
            'additional_stakeholders' => ''
        ]);
    }
}