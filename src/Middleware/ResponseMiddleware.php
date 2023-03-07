<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Middleware;

use Closure;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class ResponseMiddleware implements MiddlewareInterface
{
    public function __invoke(callable $handler): Closure
    {
        return function (RequestInterface $request, array $options) use ($handler): PromiseInterface {
            return $handler($request, $options)->then($this->mapResponse(...));
        };
    }

    abstract protected function mapResponse(ResponseInterface $request): ResponseInterface;
}