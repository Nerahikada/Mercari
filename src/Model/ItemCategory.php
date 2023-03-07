<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class ItemCategory implements ModelInterface
{
    public static function fromArray(array $array): ItemCategory
    {
        return new ItemCategory(
            (int)$array['id'],
            $array['name'],
            (int)$array['level'],
            (int)$array['level0ParentId'] ?: null,
            $array['level0ParentName'] ?: null,
            (int)$array['level1ParentId'] ?: null,
            $array['level1ParentName'] ?: null,
            (int)$array['itemBrandGroupId'],
            (int)$array['itemSizeGroupId'],
            (int)$array['displayOrder'],
            (int)$array['tabOrder'],
            array_map(fn(array $child) => ItemCategory::fromArray($child), $array['children']),
            (int)$array['parentId'] ?: null,
        );
    }

    /**
     * @param ItemCategory[] $children
     */
    private function __construct(
        public int $id,
        public string $name,
        public int $level,
        public ?int $level0ParentId,
        public ?string $level0ParentName,
        public ?int $level1ParentId,
        public ?string $level1ParentName,
        public int $itemBrandGroupId,   //nullable?
        public int $itemSizeGroupId,    //nullable?
        public int $displayOrder,
        public int $tabOrder,
        public array $children,
        public ?int $parentId,
    ) {
        foreach ($children as $child) {
            assert($child instanceof ItemCategory);
        }
    }
}