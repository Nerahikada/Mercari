<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Middleware;

use Closure;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;

abstract class RequestMiddleware implements MiddlewareInterface
{
    public function __invoke(callable $handler): Closure
    {
        return function (RequestInterface $request, array $options) use ($handler): PromiseInterface {
            return $handler($this->mapRequest($request), $options);
        };
    }

    abstract protected function mapRequest(RequestInterface $request): RequestInterface;
}