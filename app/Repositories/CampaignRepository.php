<?php

namespace App\Repositories;

use App\Models\Campaign;

class CampaignRepository implements CampaignRepositoryInterface
{
    public function getAllCampaignsWithRecords($userId, $perPage)
    {
        return Campaign::where('user_id', $userId)
            ->with('records')
            ->withCount('records')
            ->paginate($perPage);
    }

    public function getAllCampaigns($userId, $perPage)
    {
        return Campaign::where('user_id', $userId)->paginate($perPage);
    }
}
