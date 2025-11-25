<?php

namespace App\Http\Resources\City;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => [
                'id' => $this->state->id,
                'name' => $this->state->name,
                'uf' => $this->state->uf,
            ],
            'cluster' => [
                'id' => $this->cluster->id,
                'name' => $this->cluster->name,
            ],
        ];
    }
}
