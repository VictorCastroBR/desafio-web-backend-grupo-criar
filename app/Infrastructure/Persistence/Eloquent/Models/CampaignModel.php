<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignModel extends Model
{
    protected $table = 'campaigns';
    protected $fillable = ['name', 'active', 'cluster_id'];

    public function cluster()
    {
        return $this->belongsTo(ClusterModel::class, 'cluster_id');
    }

    public function discounts()
    {
        return $this->hasMany(DiscountModel::class, 'campaign_id');
    }
}
