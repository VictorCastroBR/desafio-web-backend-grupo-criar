<?php

namespace App\Domain\City\Entities;

use App\Domain\Cluster\Entities\Cluster;

class City
{
    public function __construct(
        public int $id,
        public string $name,
        public Cluster $cluster,
    ) {}
}
