<?php

namespace App\Domain\Cluster\Entities;

class Cluster
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}
}
