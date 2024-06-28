<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CampaignRepositoryInterface;


class CampaignController extends Controller
{

    protected $campaignRepository;

    function __construct(CampaignRepositoryInterface $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    function index() {
        $campaigns = $this->campaignRepository->getAllCampaignsWithRecords(Auth::id(), 10);
        $campaigns = CampaignResource::collection($campaigns);
        return Inertia::render('Campaign/Index', [
            'campaigns' => $campaigns
        ]);
    }
}
