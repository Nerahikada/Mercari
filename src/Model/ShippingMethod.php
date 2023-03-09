<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class ShippingMethod implements ModelInterface
{
    public static function fromArray(array $array): self
    {
        return new self(
            (int)$array['id'],
            $array['name'],
            (int)$array['payerId'],
            $array['type'],
            array_map(fn(array $image) => ShippingMethodImage::fromArray($image), $array['images']),
            $array['isDeprecated'],
        );
    }

    /**
     * @param ShippingMethodImage[] $images
     */
    private function __construct(
        public int $id,
        public string $name,
        public int $payerId,
        public string $type,
        public array $images,
        public bool $isDeprecated,
    ) {
        foreach ($images as $image) {
            assert($image instanceof ShippingMethodImage);
        }
    }
}