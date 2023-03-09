<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class ItemCondition implements ModelInterface
{
    public static function fromArray(array $array): self
    {
        return new self(
            (int)$array['id'],
            $array['name'],
        );
    }

    private function __construct(
        public int $id,
        public string $name,
    ) {
    }
}