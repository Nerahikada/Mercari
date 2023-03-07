<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Middleware;

use Psr\Http\Message\RequestInterface;

final class MisrepresentHeaderMiddleware extends RequestMiddleware
{
    protected function mapRequest(RequestInterface $request): RequestInterface
    {
        return $request->withHeader(
            'User-Agent',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36'
        );
    }
}