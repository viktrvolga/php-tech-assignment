<?php

declare(strict_types=1);

namespace App\PaymentGateway\Soad\Data;

use App\Common\Data\Money;
use App\PaymentGateway\Common\TransactionStatus;
use App\PaymentGateway\PaymentGatewayTransaction;

final readonly class SoadTransaction implements PaymentGatewayTransaction
{
    public function __construct(
        public string                        $id,
        public string                        $type,
        public \DateTimeImmutable            $timestamp,
        public string                        $objectType,
        public string                        $objectId,
        public int                           $amount,
        public string                        $currencyCode,
        public string                        $clientId,
        public SoadTransactionPaymentDetails $paymentDetails,
        public string                        $transactionStatus
    ) {
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function amount(): Money
    {
        return new Money($this->amount, $this->currencyCode);
    }

    public function status(): TransactionStatus
    {
        return match ($this->transactionStatus) {
            'completed' => TransactionStatus::PAID,
            default => throw new \LogicException(
                \sprintf('Unable to find mapping status rule for `%s`', $this->transactionStatus)
            )
        };
    }
}
