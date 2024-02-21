<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Services\ClientService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'document' => app(ClientService::class)->generateDocument(),
        ];
    }
}
