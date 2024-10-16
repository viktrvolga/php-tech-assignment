<?php

declare(strict_types=1);

namespace App\PaymentGateway\Common;

enum TransactionStatus: string
{
    case PAID = 'paid';
}
