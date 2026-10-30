<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => $name,
        ];
    }
}
