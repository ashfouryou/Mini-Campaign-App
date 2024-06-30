<?php 

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any campaigns.
     */
    public function viewAny(User $user)
    {
        return true; // Adjust this logic based on your requirements
    }

    /**
     * Determine whether the user can view the campaign.
     */
    public function view(User $user, Campaign $campaign)
    {
        return $user->id === $campaign->user_id;
    }

    /**
     * Determine whether the user can create campaigns.
     */
    public function create(User $user)
    {
        return true; // Adjust this logic based on your requirements
    }

    /**
     * Determine whether the user can update the campaign.
     */
    public function update(User $user, Campaign $campaign)
    {
        return $user->id === $campaign->user_id;
    }

    /**
     * Determine whether the user can delete the campaign.
     */
    public function delete(User $user, Campaign $campaign)
    {
        return $user->id === $campaign->user_id;
    }
}
