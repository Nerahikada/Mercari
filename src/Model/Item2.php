<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

use DateTimeImmutable;

final readonly class Item2 extends Item
{
    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'],
            Seller1::fromArray($array['seller']),
            ItemStatus::from($array['status']),
            $array['name'],
            $array['price'],
            $array['thumbnails'],
            $array['root_category_id'],
            $array['num_likes'],
            $array['num_comments'],
            new DateTimeImmutable('@' . $array['created']),
            new DateTimeImmutable('@' . $array['updated']),
            ItemCategory3::fromArray($array['item_category']),
            ShippingArea::fromArray($array['shipping_from_area']),
            $array['pager_id'],
            $array['liked'],
            $array['item_pv'],
        );
    }

    /**
     * @param string[] $thumbnails
     */
    private function __construct(
        public string $id,
        public Seller1 $seller,
        public ItemStatus $status,
        public string $name,
        public int $price,
        public array $thumbnails,
        public int $rootCategoryId,
        public int $numLikes,
        public int $numComments,
        public DateTimeImmutable $created,
        public DateTimeImmutable $updated,
        public ItemCategory3 $itemCategory,
        public ShippingArea $shippingFromArea,
        public int $pagerId,
        public bool $liked,
        public int $itemPv,
    ) {
        foreach ($thumbnails as $thumbnail) {
            assert(is_string($thumbnail));
        }
    }
}