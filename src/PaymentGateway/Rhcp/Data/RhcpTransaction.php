<?php

declare(strict_types=1);

namespace App\PaymentGateway\Rhcp\Data;

use App\Common\Data\Money;
use App\PaymentGateway\Common\TransactionStatus;
use App\PaymentGateway\PaymentGatewayTransaction;

final readonly class RhcpTransaction implements PaymentGatewayTransaction
{
    public function __construct(
        public string                 $webhookId,
        public string                 $event,
        public \DateTimeImmutable     $created,
        public string                 $entityType,
        public string                 $entityId,
        public int                    $totalAmount,
        public string                 $currencySymbol,
        public string                 $userId,
        public RhcpTransactionDetails $paymentInfo,
        public string                 $chargeStatus
    ) {
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function amount(): Money
    {
        return new Money($this->totalAmount, $this->currencySymbol);
    }

    public function status(): TransactionStatus
    {
        return match ($this->chargeStatus) {
            'successful' => TransactionStatus::PAID,
            default => throw new \LogicException(
                \sprintf('Unable to find mapping status rule for `%s`', $this->chargeStatus)
            )
        };
    }
}
