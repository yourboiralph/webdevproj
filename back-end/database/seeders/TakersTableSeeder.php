<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Taker;

class TakersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate 5 entries
        for ($i = 1; $i <= 5; $i++) {
            Taker::create([
                'first_name' => 'First' . $i,
                'last_name' => 'Last' . $i,
                'age' => rand(18, 50),
                'email' => 'taker' . $i . '@example.com',
                'password' => bcrypt('password'. $i)
            ]);
        }
    }
}
