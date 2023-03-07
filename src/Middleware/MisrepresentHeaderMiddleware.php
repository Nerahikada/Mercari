<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Middleware;

use Psr\Http\Message\RequestInterface;

final class MisrepresentHeaderMiddleware extends RequestMiddleware
{
    protected function mapRequest(RequestInterface $request): RequestInterface
    {
        return $request
            ->withHeader('Accept', 'application/json, text/plain, */*')
            ->withHeader('Accept-Encoding', 'gzip, deflate, br')
            ->withHeader('Accept-Language', 'ja,en-US;q=0.9,en;q=0.8')
            ->withHeader('Origin', 'https://jp.mercari.com')
            ->withHeader('Referer', 'https://jp.mercari.com/')
            ->withHeader(
                'User-Agent',
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36'
            );
    }
}