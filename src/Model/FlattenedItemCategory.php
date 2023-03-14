<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

use Nerahikada\Mercari\Exception\ArgumentOutOfRangeException;

final readonly class FlattenedItemCategory extends ItemCategory
{
    public static function fromArray(array $array): self
    {
        return new self(
            (int)$array['id'],
            $array['name'],
            (int)$array['level'],
            (int)$array['itemBrandGroupId'] ?: null,
            (int)$array['itemSizeGroupId'] ?: null,
            (int)$array['displayOrder'],
            (int)$array['tabOrder'],
            (int)$array['parentId'] ?: null,
        );
    }

    /**
     * @param int $level A level of category hierarchy (takes between 0 and 2)
     */
    private function __construct(
        public int $id,
        public string $name,
        public int $level,
        public ?int $itemBrandGroupId,
        public ?int $itemSizeGroupId,
        public int $displayOrder,
        public int $tabOrder,
        public ?int $parentId,
    ) {
        if ($level < 0 || $level > 2) {
            throw new ArgumentOutOfRangeException('$level must be between 0 and 2');
        }
    }
}