<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class ShippingMethodImage implements ModelInterface
{
    public static function fromArray(array $array): self
    {
        return new self(
            $array['deviceType'],
            $array['url'],
            $array['urlSelected'],
            $array['aspectRatio'],
        );
    }

    public function __construct(
        public string $deviceType,
        public string $url,
        public string $urlSelected,
        public float $aspectRatio,
    ) {
    }
}