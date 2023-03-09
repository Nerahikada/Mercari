<?php

declare(strict_types=1);

namespace Nerahikada\Mercari;

use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Jose\Component\Core\JWK;
use Jose\Component\KeyManagement\JWKFactory;
use Nerahikada\Mercari\Exception\ArgumentOutOfRangeException;
use Nerahikada\Mercari\Middleware\GenerateTokenMiddleware;
use Nerahikada\Mercari\Middleware\MisrepresentHeaderMiddleware;
use Nerahikada\Mercari\Model\FlattenedItemCategory;
use Nerahikada\Mercari\Model\Item;
use Nerahikada\Mercari\Model\ItemBrand;
use Nerahikada\Mercari\Model\ItemCategory;
use Nerahikada\Mercari\Model\ItemColor;
use Nerahikada\Mercari\Model\ItemCondition;
use Nerahikada\Mercari\Model\ItemSize;
use Nerahikada\Mercari\Model\NestedItemCategory;
use Nerahikada\Mercari\Model\ShippingMethod;
use Nerahikada\Mercari\Model\ShippingPayer;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use stdClass;

final readonly class Mercari
{
    private UuidInterface $uuid;
    private JWK $privateKey;
    private Client $client;

    public function __construct(Language $language = Language::Japanese)
    {
        $this->uuid = Uuid::uuid4();
        $this->privateKey = JWKFactory::createECKey('P-256');

        $stack = HandlerStack::create();
        $stack->push(new MisrepresentHeaderMiddleware($language));
        $stack->push(new GenerateTokenMiddleware($this->uuid, $this->privateKey));

        $this->client = new Client([
            'handler' => $stack,
            'headers' => ['X-Platform' => 'web'],
        ]);
    }

    private function get(string $endpoint, array $parameters = []): array
    {
        $response = $this->client->get($endpoint, ['query' => $parameters]);
        return json_decode((string)$response->getBody(), true);
    }

    private function post(string $endpoint, mixed $payload = new stdClass()): array
    {
        $response = $this->client->post($endpoint, ['json' => $payload]);
        return json_decode((string)$response->getBody(), true);
    }

    public function getUnreadNotificationCount(): int
    {
        $response = $this->post('https://api.mercari.jp/services/notification/v1/get_unread_count');
        return (int)$response['count'];
    }

    /**
     * @yield ItemCategory
     */
    public function getItemCategories(bool $flatten = true): Generator
    {
        $response = $this->post(
            'https://api.mercari.jp/services/productcatalog/v1/get_item_categories',
            [
                'showDeleted' => false,
                'flattenResponse' => $flatten,
                'pageSize' => 0,
                'pageToken' => '',
            ]
        );
        $class = $flatten ? FlattenedItemCategory::class : NestedItemCategory::class;
        foreach ($response['itemCategories'] as $category) {
            yield $class::fromArray($category);
        }
    }

    /**
     * @yield ItemBrand
     */
    public function getItemBrands(): Generator
    {
        $pageToken = '';
        do {
            $response = $this->post(
                'https://api.mercari.jp/services/productcatalog/v1/get_item_brands',
                ['pageToken' => $pageToken]
            );
            foreach ($response['itemBrands'] as $brand) {
                yield ItemBrand::fromArray($brand);
            }
        } while ($pageToken = $response['nextPageToken']);
    }

    /**
     * @yield ItemSize
     */
    public function getItemSizes(): Generator
    {
        $response = $this->get('https://api.mercari.jp/services/master/v1/itemSizes');
        foreach ($response['sizes'] as $size) {
            yield ItemSize::fromArray($size);
        }
    }

    /**
     * @yield ItemColor
     */
    public function getItemColors(): Generator
    {
        $response = $this->get('https://api.mercari.jp/services/master/v1/itemColors');
        foreach ($response['colors'] as $color) {
            yield ItemColor::fromArray($color);
        }
    }

    /**
     * @yield ShippingPayer
     */
    public function getShippingPayers(): Generator
    {
        $response = $this->get('https://api.mercari.jp/services/master/v1/shippingPayers');
        foreach ($response['payers'] as $payer) {
            yield ShippingPayer::fromArray($payer);
        }
    }

    /**
     * @yield ItemCondition
     */
    public function getItemConditions(): Generator
    {
        $response = $this->get('https://api.mercari.jp/services/master/v1/itemConditions');
        foreach ($response['conditions'] as $condition) {
            yield ItemCondition::fromArray($condition);
        }
    }

    public function getShippingMethods(): Generator
    {
        $response = $this->get('https://api.mercari.jp/services/master/v1/shippingMethods');
        foreach ($response['methods'] as $method) {
            yield ShippingMethod::fromArray($method);
        }
    }

    public function getItems(int $count = 50): Generator
    {
        if ($count < 1 || $count > 1000) {
            throw new ArgumentOutOfRangeException('$count must be between 1 and 1000');
        }
        $response = $this->get('https://api.mercari.jp/store/get_items', [
            'limit' => $count,
            //'type' => 'category', //?????
        ]);
        var_dump($response);
        foreach ($response['data'] as $item) {
            yield Item::fromArray($item);
        }
    }
}