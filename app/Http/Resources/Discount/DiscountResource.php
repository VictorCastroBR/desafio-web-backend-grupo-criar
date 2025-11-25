<?php

namespace App\Http\Resources\Discount;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'percent' => $this->percent,
            'campaign' => [
                'id' => $this->campaign->id,
                'name' => $this->campaign->name,
                'active' => $this->campaign->active,
                'cluster' => [
                    'id' => $this->campaign->cluster->id,
                    'name' => $this->campaign->cluster->name
                ]
            ]
        ];
    }
}
