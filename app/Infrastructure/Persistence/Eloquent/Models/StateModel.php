<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class StateModel extends Model
{
    protected $table = 'states';
    protected $fillable = ['name', 'uf'];

    public function cities()
    {
        return $this->hasMany(CityModel::class, 'state_id');
    }
}
