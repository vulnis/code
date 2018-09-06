<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            'value' => 1,
            'name' => 'Administrator'
        ]);
        DB::table('user')->insert([
            'value' => 1,
            'enabled' => true,
            'lockout' => false,
            'type' => 'simplerisk',
            'username' => 'Admin',
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'salt' => 'qJrxVD9EzlV43E8skvhJ',
            'password' => '$2a$15$2a2e7a2be55cd931c4784u8/wKHT.3vEYZbSMneCDuO5qzDSzNpvC',
            'last_login' => new DateTime,
            'last_password_change_date' => new DateTime,
            'teams' => ':1::2::3::4::5::6::7::8::9::10:',
            'role_id' => 1,
            'governance' => true,
            'riskmanagement' => true,
            'compliance' => true,
            'assessments' => true,
            'asset' => true,
            'admin' => true,
            'review_veryhigh' => true,
            'review_high' => true,
            'accept_mitigation' => true,
            'review_medium' => true,
            'review_low' => true,
            'review_insignificant' => true,
            'submit_risks' => true,
            'modify_risks' => true,
            'plan_mitigations' => true,
            'close_risks' => true,
            'multi_factor' => 1,
            'change_password' => false,
            'custom_display_settings' => '[\"id\",\"subject\",\"calculated_risk\",\"submission_date\",\"mitigation_planned\",\"management_review\"]',
            'add_new_frameworks' => true,
            'modify_frameworks' => true,
            'delete_frameworks' => true,
            'add_new_controls' => true,
            'modify_controls' => true,
            'delete_controls' => true,
            'add_documentation' =>true,
            'modify_documentation' => true,
            'delete_documentation' => true,
            'comment_risk_management' => true,
            'comment_compliance' => true
        ]);
    }
}
?>