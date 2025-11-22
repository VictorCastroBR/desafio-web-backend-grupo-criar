<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
    protected $table = 'cities';
    protected $fillable = ['name', 'state_id', 'cluster_id'];

    public function state()
    {
        return $this->belongsTo(StateModel::class, 'state_id');
    }

    public function cluster()
    {
        return $this->belongsTo(ClusterModel::class, 'cluster_id');
    }
}
