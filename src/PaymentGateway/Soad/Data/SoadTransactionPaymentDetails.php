<?php

declare(strict_types=1);

namespace App\PaymentGateway\Soad\Data;

final readonly class SoadTransactionPaymentDetails
{
    public function __construct(
        public string $paymentId,
        public string $method,
        public SoadTransactionPaymentDetailsCard $creditCard
    ) {
    }
}
