<?php

declare(strict_types=1);

namespace App\PaymentGateway\Nirvana;

use App\Infrastructure\Serializer\DefaultSerializer;
use App\Infrastructure\Serializer\Serializer;
use App\Infrastructure\Serializer\SerializerFormat;
use App\PaymentGateway\Nirvana\Data\NirvanaTransaction;
use App\PaymentGateway\PaymentGatewayChannel;
use App\PaymentGateway\PaymentGatewayNotificationHandler;
use App\PaymentGateway\PaymentGatewayTransaction;

final readonly class NirvanaNotificationHandler implements PaymentGatewayNotificationHandler
{
    public function __construct(
        private Serializer $serializer = new DefaultSerializer()
    ) {
    }

    public function channel(): PaymentGatewayChannel
    {
        return PaymentGatewayChannel::NIRVANA;
    }

    public function process(string $notificationPayload): PaymentGatewayTransaction
    {
        return $this->serializer->unserialize(
            payload: $notificationPayload,
            to: NirvanaTransaction::class,
            format: SerializerFormat::JSON
        );
    }
}
