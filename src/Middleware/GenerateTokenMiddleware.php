<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Middleware;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class GenerateTokenMiddleware extends RequestMiddleware
{
    public function __construct(
        private readonly UuidInterface $uuid,
        private readonly JWK $jwk
    ) {
    }

    protected function mapRequest(RequestInterface $request): RequestInterface
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
            ->addSignature($this->jwk, [
                'typ' => 'dpop+jwt',
                'alg' => 'ES256',
                'jwk' => $this->jwk->toPublic(),
            ])
            ->build();

        $token = (new CompactSerializer())->serialize($jws);

        return $request->withHeader('DPoP', $token);
    }
}