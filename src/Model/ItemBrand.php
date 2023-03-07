<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class ItemBrand implements ModelInterface
{
    public static function fromArray(array $array): self
    {
        return new self(
            (int)$array['id'],
            $array['name'],
            $array['subname'],
            $array['initial'],
            (int)$array['groupId'][0],
            $array['jaPronunciation'],
        );
    }

    private function __construct(
        public int $id,
        public string $name,
        public string $subname,
        public string $initial,
        public int $groupId,
        public string $jaPronunciation,
    ) {
    }
}