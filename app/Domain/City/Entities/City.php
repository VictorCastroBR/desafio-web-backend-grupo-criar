<?php

namespace App\Domain\City\Entities;

use App\Domain\Cluster\Entities\Cluster;
use App\Domain\State\Entities\State;

class City
{
    public function __construct(
        public int $id,
        public string $name,
        public State $state,
        public Cluster $cluster,
    ) {}
}
