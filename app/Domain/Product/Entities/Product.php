<?php

namespace App\Domain\Product\Entities;

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly float $price,
    ) {}
}
