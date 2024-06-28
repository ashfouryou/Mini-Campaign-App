<?php

namespace App\Repositories;

interface CampaignRepositoryInterface
{
    public function getAllCampaignsWithRecords($userId, $perPage);
}
