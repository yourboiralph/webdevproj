<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TakersTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(SuperUserTableSeeder::class);
        $this->call(QuestionOptionsTableSeeder::class);
        $this->call(DepressionTypesTableSeeder::class);
        $this->call(DiagnosisTableSeeder::class);
    }
}
