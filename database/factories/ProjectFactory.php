<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement(['إطلاق الموقع', 'تطبيق الجوال', 'التسويق', 'شخصي']),
            'color' => fake()->randomElement(['#6366f1', '#ec4899', '#22c55e', '#f97316']),
            'description' => null,
        ];
    }
}
