<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class ShippingPayer implements ModelInterface
{
    public static function fromArray(array $array): self
    {
        return new self(
            (int)['id'],
            $array['name'],
            $array['code'],
        );
    }

    private function __construct(
        public int $id,
        public string $name,
        public string $code,
    ) {
    }
}