<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class SellerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'access_code' => rand(0, 99999),
            'password' => Hash::make('123'),
        ];
    }
}
