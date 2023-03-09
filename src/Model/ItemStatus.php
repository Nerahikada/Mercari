<?php

declare(strict_types=1);

namespace Nerahikada\Mercari\Model;

enum ItemStatus: string
{
    case OnSale = 'on_sale';
    case Trading = 'trading';
    case SoldOut = 'sold_out';
}