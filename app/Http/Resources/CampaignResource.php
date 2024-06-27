<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_formatted' => $this->created_at->toFormattedDateString(),
            'updated_at_formatted' => $this->updated_at->toFormattedDateString(),
            'records_count' => $this->records_count,
            'records' => CampaignRecordResource::collection($this->whenLoaded('records')),
        ];
    }
}
