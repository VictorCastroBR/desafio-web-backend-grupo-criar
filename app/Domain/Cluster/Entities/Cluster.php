<?php

namespace App\Domain\Cluster\Entities;

class Cluster
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
