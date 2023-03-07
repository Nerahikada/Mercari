<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

use Nerahikada\Mercari\Exception\ArgumentOutOfRangeException;

/**
 * @property int $id
 * @property string $name
 * @property int $level A level of category hierarchy (takes between 0 and 2)
 * @property int|null $itemBrandGroupId
 * @property int|null $itemSizeGroupId
 * @property int $displayOrder
 * @property int $tabOrder
 * @property int|null $parentId
 */
abstract readonly class ItemCategory implements ModelInterface
{
    protected function checkLevelRange(int $level)
    {
        if ($level < 0 || $level > 2) {
            throw new ArgumentOutOfRangeException('$level must be between 0 and 2');
        }
    }
}