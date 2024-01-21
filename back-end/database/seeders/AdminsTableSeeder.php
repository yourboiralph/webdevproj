<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'first_name' => 'Luis',
            'last_name' => 'Suizo',
            'age' => 21,
            'email' => 'root@gmail.com',
            'password' => 'root'
        ]);

        // Generate 5 entriesTaker
        for ($i = 1; $i <= 5; $i++) {
            Admin::create([
                'first_name' => 'Admin' . $i,
                'last_name' => 'Last' . $i,
                'age' => rand(18, 50),
                'email' => 'admin' . $i . '@example.com',
                'password' => bcrypt('password'. $i)
            ]);
        }
    }
}
