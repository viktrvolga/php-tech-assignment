<?php

declare(strict_types=1);

namespace App\Common\Data;

final readonly class Money
{
    public function __construct(
        public int    $amount,
        public string $currency
    ) {

    }
}
