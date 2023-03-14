<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class ShippingArea implements ModelInterface
{
    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'],
            $array['name'],
        );
    }

    private function __construct(
        public int $id,
        public string $name,
    ) {
    }
}