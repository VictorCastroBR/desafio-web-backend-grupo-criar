<?php

namespace App\Domain\Discount\Entities;

use App\Domain\Campaign\Entities\Campaign;

class Discount
{
    public function __construct(
        public int $id,
        public ?float $value,
        public ?float $percent,
        public Campaign $campaign
    ) {}
}
