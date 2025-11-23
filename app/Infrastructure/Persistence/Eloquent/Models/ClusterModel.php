<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class ClusterModel extends Model
{
    protected $table = 'clusters';
    protected $fillable = ['name'];

    public function cities()
    {
        return $this->hasMany(CityModel::class, 'cluster_id');
    }
}
