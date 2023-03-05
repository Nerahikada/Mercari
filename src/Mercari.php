<?php

declare(strict_types=1);

namespace Nerahikada\Mercari;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class Mercari
{
    private Client $client;
    private UuidInterface $uuid;
    private JWK $privateKey;

    public function __construct()
    {
        $stack = HandlerStack::create();
        $stack->push(Middleware::mapRequest($this->misrepresentHeaders(...)));
        $stack->push(Middleware::mapRequest($this->generateToken(...)));

        $this->client = new Client([
            'debug' => true,
            'handler' => $stack,
            'headers' => ['X-Platform' => 'web'],
            'http_errors' => false,
        ]);

        $this->uuid = Uuid::uuid4();
        $this->privateKey = JWKFactory::createECKey('P-256');
    }

    public function get(string $endpoint): string
    {
        $r = $this->client->get($endpoint);
        return (string)$r->getBody();
    }

    private function misrepresentHeaders(RequestInterface $request): RequestInterface
    {
        return $request->withHeader(
            'User-Agent',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36'
        );
    }

    private function generateToken(RequestInterface $request): RequestInterface
    {
        $jws = (new JWSBuilder(new AlgorithmManager([new ES256()])))
            ->create()
            ->withPayload(
                json_encode([
                    'iat' => time(),
                    'jti' => Uuid::uuid4(),
                    'htu' => $request->getUri(),
                    'htm' => $request->getMethod(),
                    'uuid' => $this->uuid,
                ])
            )
            ->addSignature($this->privateKey, [
                'typ' => 'dpop+jwt',
                'alg' => 'ES256',
                'jwk' => $this->privateKey->toPublic(),
            ])
            ->build();

        $token = (new CompactSerializer())->serialize($jws);

        return $request->withHeader('DPoP', $token);
    }
}