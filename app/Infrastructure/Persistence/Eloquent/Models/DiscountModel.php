<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountModel extends Model
{
    protected $table = 'discounts';
    protected $fillable = ['value', 'percent', 'campaign_id'];

    public function campaign()
    {
        return $this->belongsTo(CampaignModel::class, 'campaign_id');
    }
}
