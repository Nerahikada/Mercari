<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $price
 * @property string[] $thumbnails
 * @property ItemStatus $status
 * @property int $numLikes
 * @property int $numComments
 * @property int $pagerId
 */
abstract readonly class Item implements ModelInterface
{
}