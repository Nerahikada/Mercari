<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

class ItemColor implements ModelInterface
{
    public static function fromArray(array $array): self
    {
        return new self(
            (int)$array['id'],
            $array['name'],
            $array['rgb'],
        );
    }

    private function __construct(
        public int $id,
        public string $name,
        public string $rgb,
    ) {
    }
}