<?php

namespace App\Domain\Campaign\Entities;

use App\Domain\Cluster\Entities\Cluster;

class Campaign
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly bool $active,
        public readonly Cluster $cluster
    ) {}
}
