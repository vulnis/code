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
        $this->call([
            RoleTableSeeder::class,
            CategoryTableSeeder::class,
            SourceTableSeeder::class,
            StageTableSeeder::class,
            UserTableSeeder::class,
            RiskTableSeeder::class,
            ConsequenceTableSeeder::class,
            CauseTableSeeder::class,
            CauseConsequenceSeeder::class,
            ResponsibleTableSeeder::class,
            SeverityTableSeeder::class,
            ProbabilityTableSeeder::class,
            LevelTableSeeder::class,
            AssessmentTableSeeder::class,
            MitigationTableSeeder::class,
            AssetTableSeeder::class,
            ComponentTableSeeder::class,
        ]);
    }
}
?>