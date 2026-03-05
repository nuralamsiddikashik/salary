<?php

namespace Database\Seeders;

use App\Models\Employee;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    use WithoutModelEvents;

    /*
     * Seed the application's database.
     */
    public function run(): void {
        \App\Models\User::firstOrCreate(
            ['email' => 'ashikeron@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make( 'Admin@Controller123' ),
            ]
        );

        Employee::factory( 5 )->create();
    }
}
