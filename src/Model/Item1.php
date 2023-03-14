<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

final readonly class Item1 extends Item
{
    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'],
            $array['name'],
            $array['price'],
            $array['thumbnails'],
            ItemStatus::from($array['status']),
            $array['num_likes'],
            $array['num_comments'],
            $array['pager_id'],
        );
    }

    /**
     * @param string[] $thumbnails
     */
    private function __construct(
        public string $id,
        public string $name,
        public int $price,
        public array $thumbnails,
        public ItemStatus $status,
        public int $numLikes,
        public int $numComments,
        public int $pagerId,
    ) {
        foreach ($thumbnails as $thumbnail) {
            assert(is_string($thumbnail));
        }
    }
}