<?php

declare(strict_types=1);

namespace App\PaymentGateway\Rhcp\Data;

final readonly class RhcpTransactionDetailsCard
{
    public function __construct(
        public string $issuer,
        public string $last_four,
        public string $expirationMonth,
        public int $expirationYear
    ) {
    }
}
