<?php

declare(strict_types=1);

namespace App\PaymentGateway;

enum PaymentGatewayChannel: string
{
    case NIRVANA = 'nirvana';
    case RHCP = 'rhcp';
    case SOAD = 'soad';
}
