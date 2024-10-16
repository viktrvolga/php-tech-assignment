<?php

declare(strict_types=1);

namespace App\PaymentGateway;

use App\PaymentGateway\Exceptions\PaymentGatewayException;

/**
 *
 */
final class NotificationProcessor
{
    /**
     * @var PaymentGatewayNotificationHandler[]
     */
    private array $channelHandlers = [];

    /**
     * @param PaymentGatewayNotificationHandler[] $handlerCollection
     */
    public function __construct(array $handlerCollection)
    {
        foreach ($handlerCollection as $channelHandler) {
            $this->channelHandlers[$channelHandler->channel()->name] = $channelHandler;
        }
    }

    public function handle(string $payload, PaymentGatewayChannel $channel): PaymentGatewayTransaction
    {
        if ($payload === '') {
            throw new PaymentGatewayException('Unable to process empty callback payload');
        }

        $channelHandler = $this->channelHandlers[$channel->name]
            ?? throw new PaymentGatewayException(
                \sprintf('Unable to find handler for `%s` channel', $channel->name)
            );

        return $channelHandler->process($payload);
    }
}
