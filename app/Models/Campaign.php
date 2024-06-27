<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function records(){
        return $this->hasMany(CampaignRecord::class);
    }
}
