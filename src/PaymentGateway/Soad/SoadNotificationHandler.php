<?php

declare(strict_types=1);

namespace App\PaymentGateway\Soad;

use App\Infrastructure\Serializer\DefaultSerializer;
use App\Infrastructure\Serializer\Serializer;
use App\Infrastructure\Serializer\SerializerFormat;
use App\PaymentGateway\PaymentGatewayChannel;
use App\PaymentGateway\PaymentGatewayNotificationHandler;
use App\PaymentGateway\PaymentGatewayTransaction;
use App\PaymentGateway\Soad\Data\SoadTransaction;

final readonly class SoadNotificationHandler implements PaymentGatewayNotificationHandler
{
    public function __construct(
        private Serializer $serializer = new DefaultSerializer()
    ) {
    }

    public function channel(): PaymentGatewayChannel
    {
        return PaymentGatewayChannel::SOAD;
    }

    public function process(string $notificationPayload): PaymentGatewayTransaction
    {
        return $this->serializer->unserialize(
            payload: $notificationPayload,
            to: SoadTransaction::class,
            format: SerializerFormat::JSON
        );
    }
}
