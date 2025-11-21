<?php

namespace App\Domain\Campaign\Entities;

use App\Domain\Cluster\Entities\Cluster;

class Campaign
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $active,
        public Cluster $cluster
    ) {}
}
