<?php

declare(strict_types=1);

namespace Nerahikada\Mercari;

use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Jose\Component\Core\JWK;
use Jose\Component\KeyManagement\JWKFactory;
use Nerahikada\Mercari\Middleware\GenerateTokenMiddleware;
use Nerahikada\Mercari\Middleware\MisrepresentHeaderMiddleware;
use Nerahikada\Mercari\Model\ItemCategory;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use stdClass;

final readonly class Mercari
{
    private UuidInterface $uuid;
    private JWK $privateKey;
    private Client $client;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->privateKey = JWKFactory::createECKey('P-256');

        $stack = HandlerStack::create();
        $stack->push(new MisrepresentHeaderMiddleware());
        $stack->push(new GenerateTokenMiddleware($this->uuid, $this->privateKey));

        $this->client = new Client([
            'handler' => $stack,
            'headers' => ['X-Platform' => 'web'],
        ]);
    }

    private function get(string $endpoint): array
    {
        $response = $this->client->get($endpoint);
        return json_decode((string)$response->getBody(), true);
    }

    private function post(string $endpoint, mixed $payload): array
    {
        $response = $this->client->post($endpoint, ['json' => $payload]);
        return json_decode((string)$response->getBody(), true);
    }

    public function getUnreadNotificationCount(): int
    {
        $response = $this->post(
            'https://api.mercari.jp/services/notification/v1/get_unread_count',
            new stdClass()
        );
        return (int)$response['count'];
    }

    /**
     * @yield ItemCategory
     */
    public function getItemCategories(): Generator
    {
        $pageToken = '';
        do {
            $response = $this->post(
                'https://api.mercari.jp/services/productcatalog/v1/get_item_categories',
                [
                    'showDeleted' => false,
                    'flattenResponse' => false,
                    'pageSize' => 0,
                    'pageToken' => $pageToken,
                ]
            );
            foreach ($response['itemCategories'] as $category) {
                yield ItemCategory::fromArray($category);
            }
        } while ($pageToken = $response['nextPageToken']);
    }

    public function getItemSizes(): array
    {
        $response = $this->get('https://api.mercari.jp/services/master/v1/itemSizes');
        return $response;
    }
}