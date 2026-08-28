<?php

namespace Database\Factories;

use App\Models\Order; // Import the Order model
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth; // Import the Auth facade to access authenticated user

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1, // Use the authenticated user's ID
            'type_id' => 1,
            'type' => 'Item',
            'txn_no' => $this->faker->unique()->numerify('########-UID##-####'), // Generate a unique transaction number
            'sub_total' => 1000, // Generate a random sub-total
            'total' => 1000, // Generate a random total
            'status' => $this->faker->randomElement([1, 2, 3, 4]), // Generate a random status
            'payment_status' => $this->faker->randomElement([1, 2]), // Generate a random payment status
            'payment_gateway' => 'COD', // Generate a random payment gateway
        ];
    }
}
