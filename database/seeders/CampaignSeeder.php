<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\CampaignRecord;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(){
        Campaign::factory(10)->create()->each(function ($campaign) {
            CampaignRecord::factory(5)->create(['campaign_id' => $campaign->id]);
        });
    }
}
