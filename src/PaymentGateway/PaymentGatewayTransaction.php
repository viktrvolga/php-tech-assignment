<?php

declare(strict_types=1);

namespace App\PaymentGateway;

use App\Common\Data\Money;
use App\PaymentGateway\Common\TransactionStatus;

interface PaymentGatewayTransaction
{
    public function occurredAt(): \DateTimeImmutable;

    public function amount(): Money;

    public function status(): TransactionStatus;
}
