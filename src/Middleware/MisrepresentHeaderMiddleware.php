<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Middleware;

use Nerahikada\Mercari\Language;
use Psr\Http\Message\RequestInterface;

final class MisrepresentHeaderMiddleware extends RequestMiddleware
{
    public function __construct(
        private readonly Language $language
    ) {
    }

    protected function mapRequest(RequestInterface $request): RequestInterface
    {
        return $request
            ->withHeader('Accept', 'application/json, text/plain, */*')
            ->withHeader('Accept-Encoding', 'gzip, deflate, br')
            ->withHeader('Accept-Language', $this->language->value)
            ->withHeader('Origin', 'https://jp.mercari.com')
            ->withHeader('Referer', 'https://jp.mercari.com/')
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36');
    }
}