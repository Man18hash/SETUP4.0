<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade

class ProjectPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert multiple rows with plan_month values: 0.5, 3, 4, 5, 6
        $plans = [
            ['plan_month' => 0.5],
            ['plan_month' => 3],
            ['plan_month' => 4],
            ['plan_month' => 5],
            ['plan_month' => 6],
        ];

        DB::table('project_plan')->insert($plans);
    }
}
