<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

interface ModelInterface
{
    public static function fromArray(array $array): self;
}