<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Added import for DB facade

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('project_type')->insert([
            'project_type' => 'SETUP4.0'
        ]);
    }
}
