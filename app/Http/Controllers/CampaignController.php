<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CampaignResource;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CampaignRepositoryInterface;
use App\Http\Requests\CampaignRequest;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{

    protected $campaignRepository;

    function __construct(CampaignRepositoryInterface $campaignRepository){
        $this->campaignRepository = $campaignRepository;
    }

    function index() {
        $campaigns = $this->campaignRepository->getAllCampaignsWithRecords(Auth::id(), 10);
        return Inertia::render('Campaign/Index', [
            'campaigns' => CampaignResource::collection($campaigns)
        ]);
    }

    function create() {
        return Inertia::render('Campaign/Create');
    }

    public function store(CampaignRequest $request){
        $filePath = $request->file('file')->store('uploads');
        $result = $this->campaignRepository->createCampaign($request->validated(), $filePath);
        if ($result['hasErrors']) {
            return Inertia::render('Campaign/Create', [
                'errorFileName' => $result['errorFileName'],
                'errors' => session('errors')
            ]);
        }
        return redirect()->route('campaign.index');
    }

    public function destroy($campaignId){
        $this->campaignRepository->deleteCampaign($campaignId);
        return redirect()->route('campaign.index');
    }

    public function show($campaignId){
        $campaign = $this->campaignRepository->getCampaignById($campaignId);
        return Inertia::render('Campaign/Create', [
            'campaign' => new CampaignResource($campaign)
        ]);
    }

    public function update(CampaignRequest $request, $id){
        $filePath = $request->file('file') ? $request->file('file')->store('uploads') : null;
        $result = $this->campaignRepository->updateCampaign($id, $request->validated(), $filePath);
        if ($result['hasErrors']) {
            return Inertia::render('Campaign/Create', [
                'errorFileName' => $result['errorFileName'],
                'campaign' => new CampaignResource($this->campaignRepository->getCampaignById($id)),
                'errors' => session('errors')
            ]);
        }
    
        return redirect()->route('campaign.index');
    }

}
