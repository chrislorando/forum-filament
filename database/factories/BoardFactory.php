<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Board>
 */
class BoardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Diskusi Umum', 'Berita Terkini', 'Analisis Pasar', 'Tutorial', 'Pertanyaan & Jawaban',
            'Trading Signals', 'ICO Review', 'Airdrop', 'Diskusi Bitcoin', 'Diskusi Ethereum',
            'Diskusi Solana', 'Diskusi Altcoins', 'Technical Analysis', 'Fundamental Analysis',
            'Market Updates', 'Price Discussion', 'Mining Discussion', 'Staking Rewards',
            'DeFi Projects', 'NFT Marketplace', 'Crypto Adoption', 'Regulation News',
            'Security Tips', 'Wallet Discussion', 'Exchange Review', 'Project Announcement',
            'Community Events', 'Meme Coins', 'Yield Farming', 'Liquidity Mining'
        ];

        $name = fake()->unique()->randomElement($names);

        return [
            'category_id' => \App\Models\Category::factory(),
            'parent_id' => null,
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => fake()->optional()->paragraph(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
