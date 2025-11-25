<?php

namespace App\Domain\Discount\Entities;

use App\Domain\Campaign\Entities\Campaign;

class Discount
{
    public function __construct(
        public readonly int $id,
        public readonly ?float $value,
        public readonly ?float $percent,
        public readonly Campaign $campaign
    ) {}
}
