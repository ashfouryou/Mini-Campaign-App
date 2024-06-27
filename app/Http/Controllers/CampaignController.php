<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    function index() {
        $campaigns = Campaign::where('user_id', Auth::id())
        ->with('records') 
        ->withCount('records')
        ->get();
        $campaigns = CampaignResource::collection($campaigns);
        return Inertia::render('Campaign/Index', [
            'campaigns' => $campaigns
        ]);
    }
}
