<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Campaign;
use App\Models\CampaignRecord;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CampaignRecord>
 */
class CampaignRecordFactory extends Factory
{

    protected $model = CampaignRecord::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'content' => $this->faker->paragraph,
            'campaign_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
