<?php

namespace App\Domain\State\Entities;

class State
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $uf,
    ) {}
}
