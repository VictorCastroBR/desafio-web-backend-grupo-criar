<?php

namespace App\Domain\City\Entities;

use App\Domain\Cluster\Entities\Cluster;
use App\Domain\State\Entities\State;

class City
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly State $state,
        public readonly Cluster $cluster,
    ) {}
}
