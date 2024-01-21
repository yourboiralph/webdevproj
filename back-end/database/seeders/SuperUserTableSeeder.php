<?php

namespace Database\Seeders;

use App\Models\SuperUser;
use Illuminate\Database\Seeder;

class SuperUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SuperUser::create([
            'adminID' => 1,
        ]);
    }
}
