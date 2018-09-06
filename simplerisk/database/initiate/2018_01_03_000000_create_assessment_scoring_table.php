<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssessmentScoringTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assessment_scoring', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('scoring_method');
			$table->float('calculated_risk', 10, 0);
			$table->float('CLASSIC_likelihood', 10, 0)->default(5);
			$table->float('CLASSIC_impact', 10, 0)->default(5);
			$table->string('CVSS_AccessVector', 3)->default('N');
			$table->string('CVSS_AccessComplexity', 3)->default('L');
			$table->string('CVSS_Authentication', 3)->default('N');
			$table->string('CVSS_ConfImpact', 3)->default('C');
			$table->string('CVSS_IntegImpact', 3)->default('C');
			$table->string('CVSS_AvailImpact', 3)->default('C');
			$table->string('CVSS_Exploitability', 3)->default('ND');
			$table->string('CVSS_RemediationLevel', 3)->default('ND');
			$table->string('CVSS_ReportConfidence', 3)->default('ND');
			$table->string('CVSS_CollateralDamagePotential', 3)->default('ND');
			$table->string('CVSS_TargetDistribution', 3)->default('ND');
			$table->string('CVSS_ConfidentialityRequirement', 3)->default('ND');
			$table->string('CVSS_IntegrityRequirement', 3)->default('ND');
			$table->string('CVSS_AvailabilityRequirement', 3)->default('ND');
			$table->integer('DREAD_DamagePotential')->nullable()->default(10);
			$table->integer('DREAD_Reproducibility')->nullable()->default(10);
			$table->integer('DREAD_Exploitability')->nullable()->default(10);
			$table->integer('DREAD_AffectedUsers')->nullable()->default(10);
			$table->integer('DREAD_Discoverability')->nullable()->default(10);
			$table->integer('OWASP_SkillLevel')->nullable()->default(10);
			$table->integer('OWASP_Motive')->nullable()->default(10);
			$table->integer('OWASP_Opportunity')->nullable()->default(10);
			$table->integer('OWASP_Size')->nullable()->default(10);
			$table->integer('OWASP_EaseOfDiscovery')->nullable()->default(10);
			$table->integer('OWASP_EaseOfExploit')->nullable()->default(10);
			$table->integer('OWASP_Awareness')->nullable()->default(10);
			$table->integer('OWASP_IntrusionDetection')->nullable()->default(10);
			$table->integer('OWASP_LossOfConfidentiality')->nullable()->default(10);
			$table->integer('OWASP_LossOfIntegrity')->nullable()->default(10);
			$table->integer('OWASP_LossOfAvailability')->nullable()->default(10);
			$table->integer('OWASP_LossOfAccountability')->nullable()->default(10);
			$table->integer('OWASP_FinancialDamage')->nullable()->default(10);
			$table->integer('OWASP_ReputationDamage')->nullable()->default(10);
			$table->integer('OWASP_NonCompliance')->nullable()->default(10);
			$table->integer('OWASP_PrivacyViolation')->nullable()->default(10);
			$table->float('Custom', 10, 0)->nullable()->default(10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('assessment_scoring');
	}

}
