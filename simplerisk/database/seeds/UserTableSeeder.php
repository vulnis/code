<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
        DB::table('user')->insert([
            'value' => 2,
            'enabled' => false,
            'lockout' => true,
            'type' => 'simplerisk',
            'username' => 'Batch',
            'name' => 'Batch-process',
            'email' => 'batch@simplerisk.com',
            'salt' => '',
            'password' => '',
            'last_login' => new DateTime,
            'last_password_change_date' => new DateTime,
            'teams' => '',
            'role_id' => 3,
            'governance' => false,
            'riskmanagement' => false,
            'compliance' => false,
            'assessments' => false,
            'asset' => false,
            'admin' => false,
            'review_veryhigh' => false,
            'review_high' => false,
            'accept_mitigation' => false,
            'review_medium' => false,
            'review_low' => false,
            'review_insignificant' => false,
            'submit_risks' => false,
            'modify_risks' => false,
            'plan_mitigations' => false,
            'close_risks' => false,
            'multi_factor' => 1,
            'change_password' => false,
            'custom_display_settings' => '',
            'add_new_frameworks' => false,
            'modify_frameworks' => false,
            'delete_frameworks' => false,
            'add_new_controls' => false,
            'modify_controls' => false,
            'delete_controls' => false,
            'add_documentation' =>false,
            'modify_documentation' => false,
            'delete_documentation' => false,
            'comment_risk_management' => false,
            'comment_compliance' => false
        ]);
    }
}
?>