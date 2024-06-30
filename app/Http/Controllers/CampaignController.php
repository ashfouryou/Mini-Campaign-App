<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CampaignResource;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CampaignRepositoryInterface;
use App\Http\Requests\CampaignRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Builder;



class CampaignController extends Controller
{

    protected $campaignRepository;
    protected $emailService;

    function __construct(CampaignRepositoryInterface $campaignRepository){
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @author Ashish Kumar
     */
    function index(Request $request) {
        $this->authorize('viewAny', Campaign::class);
        $search = $request->query('search');
        $campaigns = $this->campaignRepository->getAllCampaignsWithRecords(Auth::id(), 5,$search);
        return Inertia::render('Campaign/Index', [
            'campaigns' => CampaignResource::collection($campaigns)
        ]);
    }
     
    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     * @author Ashish Kumar
     */
    function create() {
        $this->authorize('create', Campaign::class);
        return Inertia::render('Campaign/Create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Http\Requests\CampaignRequest  $request
     * @return \Illuminate\Http\Response
     * @author Ashish Kumar
     */
    public function store(CampaignRequest $request){
        $this->authorize('create', Campaign::class);
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

    /**
     * Remove the specified resource from storage.
     * @param  int  $campaignId
     * @author Ashish Kumar
     */
    public function destroy($campaignId){
        $campaign = $this->campaignRepository->getCampaignById($campaignId);
        $this->authorize('delete', $campaign);
        $this->campaignRepository->deleteCampaign($campaignId);
        return redirect()->route('campaign.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $campaignId
     * @author Ashish Kumar
     */
    public function show($campaignId){
        $campaign = $this->campaignRepository->getCampaignById($campaignId);
        $this->authorize('view', $campaign);
        return Inertia::render('Campaign/Create', [
            'campaign' => new CampaignResource($campaign)
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\CampaignRequest  $request
     * @param  int  $id
     * @author Ashish Kumar
     */
    public function update(CampaignRequest $request, $id){
        $campaign = $this->campaignRepository->getCampaignById($id);
        $this->authorize('update', $campaign);
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
