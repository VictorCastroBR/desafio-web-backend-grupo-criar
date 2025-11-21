<?php

namespace App\Domain\State\Entities;

class State
{
    public function __construct(
        public int $id,
        public string $name,
        public string $uf,
    ) {}
}
