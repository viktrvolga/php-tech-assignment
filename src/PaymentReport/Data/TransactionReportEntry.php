<?php

declare(strict_types=1);

namespace App\PaymentReport\Data;

use App\PaymentGateway\PaymentGatewayTransaction;

final readonly class TransactionReportEntry
{
    public function __construct(
        public \DateTimeImmutable $dateTime,
        public string             $status,
        public int                $total
    ) {
    }

    public static function fromTransaction(PaymentGatewayTransaction $transaction): self
    {
        return new self(
            dateTime: $transaction->occurredAt(),
            status: $transaction->status()->value,
            total: $transaction->amount()->amount
        );
    }
}
