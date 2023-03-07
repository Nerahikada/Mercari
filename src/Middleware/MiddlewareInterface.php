<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Middleware;

use Closure;

interface MiddlewareInterface
{
    public function __invoke(callable $handler): Closure;
}