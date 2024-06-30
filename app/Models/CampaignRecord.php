<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignRecord extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id', 'name', 'email', 'content', 'campaign_date', 'status'];

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }
}
