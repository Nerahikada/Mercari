<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class Seller1 extends Seller
{
    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'],
            $array['name'],
        );
    }

    /**
     * @param string $name The value is always "dont-use-this"
     */
    private function __construct(
        public int $id,
        public string $name,
    ) {
        assert($name === 'dont-use-this');
    }
}