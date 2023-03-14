<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class ItemCategory3 extends ItemCategory
{
    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'],
            $array['name'],
            $array['display_order'],
            $array['parent_category_id'],
            $array['parent_category_name'],
            $array['root_category_id'],
            $array['root_category_name'],
        );
    }

    private function __construct(
        public int $id,
        public string $name,
        public int $displayOrder,
        public int $parentCategoryId,
        public string $parentCategoryName,
        public int $rootCategoryId,
        public string $rootCategoryName,
    ) {
    }
}