<?php

declare(strict_types=1);

namespace App\PaymentGateway;

/**
 * Theoretically, we could abandon this interface and within the context of the task it would be ok, since in the
 * context of each of the providers there is no logic for working with transactions. They just need to be deserialized.
 * But let's leave it as an extension point.
 */
interface PaymentGatewayNotificationHandler
{
    public function channel(): PaymentGatewayChannel;

    /**
     * @param non-empty-string $notificationPayload
     * @return PaymentGatewayTransaction
     */
    public function process(string $notificationPayload): PaymentGatewayTransaction;
}
