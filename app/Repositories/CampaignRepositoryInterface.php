<?php

namespace App\Repositories;

interface CampaignRepositoryInterface {
    public function getAllCampaignsWithRecords($userId, $perPage);
    public function createCampaign(array $data, $filePath);
    public function getCampaignById($id);
    public function updateCampaign($id, array $data);
    public function deleteCampaign($id);
}
