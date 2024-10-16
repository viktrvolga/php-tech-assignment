<?php

declare(strict_types=1);

namespace App\PaymentGateway\Nirvana\Data;

final readonly class NirvanaTransactionCardPaymentMethod
{
    public function __construct(
        public string $brand,
        public string $last4,
        public string $expMonth,
        public int    $expYear
    ) {
    }
}
