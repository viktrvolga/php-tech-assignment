<?php

declare(strict_types=1);

namespace App\PaymentGateway\Nirvana\Data;

final readonly class NirvanaTransactionPaymentMethod
{
    public function __construct(
        public string $id,
        public string $type,
        public NirvanaTransactionCardPaymentMethod $card,
    ) {
    }
}
