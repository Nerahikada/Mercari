<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class ItemSize implements ModelInterface
{
    public static function fromArray(array $array): self
    {
        return new self(
            (int)$array['id'],
            $array['name'],
            (int)$array['groupId'],
            $array['group'],
        );
    }

    private function __construct(
        public int $id,
        public string $name,
        public int $groupId,
        public string $group,
    ) {
    }
}