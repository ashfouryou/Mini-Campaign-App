<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'description'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function records(){
        return $this->hasMany(CampaignRecord::class);
    }
}
