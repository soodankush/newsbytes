<?php

namespace Database\Factories;

use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Url>
 */
class UrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hashed_url' => Url::hashUrl('https://documenter.getpostman.com/view/8610000/2s93mAUzwh'),
            'long_url' => 'https://documenter.getpostman.com/view/8610000/2s93mAUzwh',
            'click_counts' => 0,
            'user_id' => null,
            'single_use' => 0,
            'ownership_type' => 0,
            'active' => 1
        ];
    }
}
