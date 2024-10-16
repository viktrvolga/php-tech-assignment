<?php

declare(strict_types=1);

namespace App\PaymentGateway\Nirvana\Data;

use App\Common\Data\Money;
use App\PaymentGateway\Common\TransactionStatus;
use App\PaymentGateway\PaymentGatewayTransaction;

final readonly class NirvanaTransaction implements PaymentGatewayTransaction
{
    public function __construct(
        public string                          $eventId,
        public string                          $eventType,
        public \DateTimeImmutable              $createdAt,
        public string                          $resourceType,
        public string                          $resourceId,
        public int                             $amountReceived,
        public string                          $currency,
        public string                          $customerId,
        public NirvanaTransactionPaymentMethod $paymentMethod,
        public string                          $status
    ) {
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function amount(): Money
    {
        return new Money($this->amountReceived, $this->currency);
    }

    public function status(): TransactionStatus
    {
        return match ($this->status) {
            'succeeded' => TransactionStatus::PAID,
            default => throw new \LogicException(
                \sprintf('Unable to find mapping status rule for `%s`', $this->status)
            )
        };
    }
}
