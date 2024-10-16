<?php

declare(strict_types=1);

namespace App\PaymentGateway\Rhcp\Data;

final readonly class RhcpTransactionDetails
{
    public function __construct(
        public string                     $paymentMethodId,
        public string                     $paymentType,
        public RhcpTransactionDetailsCard $debitCard,
    ) {

    }
}
