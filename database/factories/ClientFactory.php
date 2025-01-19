<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $creditAllowed =$this->faker->boolean();
        $creditLimit = $this->faker->randomFloat(2,1000,10000);
        return [
            'name'=>$this->faker->name(),
            'phone'=>$this->faker->unique()->numerify('01#########'),
            'line_phone'=>$this->faker->unique()->numerify('0########'),
            'address'=>$this->faker->address(),
            'credit_allowed'=>$creditAllowed,
            // if credit allowed assign random limit
            'credit_limit'=>$creditAllowed ? $creditLimit : 0,
            // if credit is allowed assign random balance number that is less than the credit limit
            'current_balance'=>$creditAllowed ? $this->faker->randomFloat(2,1,$creditLimit) : 0
        ];
    }
}
