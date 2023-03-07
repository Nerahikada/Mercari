<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class NestedItemCategory extends ItemCategory
{
    public static function fromArray(array $array): self
    {
        return new self(
            (int)$array['id'],
            $array['name'],
            (int)$array['level'],
            (int)$array['level0ParentId'] ?: null,
            $array['level0ParentName'] ?: null,
            (int)$array['level1ParentId'] ?: null,
            $array['level1ParentName'] ?: null,
            (int)$array['itemBrandGroupId'] ?: null,
            (int)$array['itemSizeGroupId'] ?: null,
            (int)$array['displayOrder'],
            (int)$array['tabOrder'],
            array_map(fn(array $child) => self::fromArray($child), $array['children']),
            (int)$array['parentId'] ?: null,
        );
    }

    /**
     * @param self[] $children
     */
    private function __construct(
        public int $id,
        public string $name,
        public int $level,
        public ?int $level0ParentId,
        public ?string $level0ParentName,
        public ?int $level1ParentId,
        public ?string $level1ParentName,
        public ?int $itemBrandGroupId,
        public ?int $itemSizeGroupId,
        public int $displayOrder,
        public int $tabOrder,
        public array $children,
        public ?int $parentId,
    ) {
        $this->checkLevelRange($level);
        foreach ($children as $child) {
            assert(is_a($child, self::class));
        }
    }
}