<?php

declare(strict_types=1);

namespace App\PaymentGateway\Soad\Data;

final readonly class SoadTransactionPaymentDetailsCard
{
    public function __construct(
        public string $network,
        public string $lastDigits,
        public string $expiryMonth,
        public int    $expiryYear
    ) {
    }
}
