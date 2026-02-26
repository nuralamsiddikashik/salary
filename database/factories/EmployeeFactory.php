<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory {
    public function definition(): array {
        $totalSalary = $this->faker->numberBetween( 20000, 80000 );

        return [
            'name'         => $this->faker->name(),
            'join_date'    => $this->faker->date(),
            'designation'  => $this->faker->jobTitle(),

            'total_salary' => $totalSalary,

            // Fixed breakdown (example 50/30/10/10)
            'basic_salary' => $totalSalary * 0.50,
            'house_rent'   => $totalSalary * 0.30,
            'medical'      => $totalSalary * 0.10,
            'conveyance'   => $totalSalary * 0.10,
        ];
    }
}