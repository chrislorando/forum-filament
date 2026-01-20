<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Bitcoin', 'Ekonomi', 'Altcoins', 'Trading', 'Mining',
            'Teknologi Blockchain', 'Regulasi Crypto', 'NFT & DeFi',
            'Ethereum', 'DeFi', 'Solana', 'Cardano', 'Polygon',
            'Aave', 'Uniswap', 'Chainlink', 'Polkadot', 'Avalanche',
            'Cosmos', 'Binance', 'Kripto Indonesia', 'Diskusi Umum', 'Berita'
        ];

        $name = fake()->unique()->randomElement($names);

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => fake()->optional(0.7)->paragraph(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
