<?php

namespace App\Domain\Product\Entities;

class Product
{
    public function __construct(
        public int $id,
        public string $name,
        public float $price,
    ) {}
}
