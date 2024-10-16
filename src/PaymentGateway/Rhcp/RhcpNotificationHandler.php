<?php

declare(strict_types=1);

namespace App\PaymentGateway\Rhcp;

use App\Infrastructure\Serializer\DefaultSerializer;
use App\Infrastructure\Serializer\Serializer;
use App\Infrastructure\Serializer\SerializerFormat;
use App\PaymentGateway\PaymentGatewayChannel;
use App\PaymentGateway\PaymentGatewayNotificationHandler;
use App\PaymentGateway\PaymentGatewayTransaction;
use App\PaymentGateway\Rhcp\Data\RhcpTransaction;

final readonly class RhcpNotificationHandler implements PaymentGatewayNotificationHandler
{
    public function __construct(
        private Serializer $serializer = new DefaultSerializer()
    ) {
    }

    public function channel(): PaymentGatewayChannel
    {
        return PaymentGatewayChannel::RHCP;
    }

    public function process(string $notificationPayload): PaymentGatewayTransaction
    {
        return $this->serializer->unserialize(
            payload: $notificationPayload,
            to: RhcpTransaction::class,
            format: SerializerFormat::XML
        );
    }
}
