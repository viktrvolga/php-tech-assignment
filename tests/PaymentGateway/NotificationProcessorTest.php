<?php

declare(strict_types=1);

namespace Tests\PaymentGateway;

use App\PaymentGateway\Common\TransactionStatus;
use App\PaymentGateway\Exceptions\PaymentGatewayException;
use App\PaymentGateway\Nirvana\NirvanaNotificationHandler;
use App\PaymentGateway\NotificationProcessor;
use App\PaymentGateway\PaymentGatewayChannel;
use App\PaymentGateway\Rhcp\RhcpNotificationHandler;
use App\PaymentGateway\Soad\SoadNotificationHandler;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NotificationProcessorTest extends TestCase
{
    public function testHandleForUnknownChannel()
    {
        $this->expectException(PaymentGatewayException::class);
        $this->expectExceptionMessage('Unable to find handler for `SOAD` channel');

        (new NotificationProcessor([]))->handle('{}', PaymentGatewayChannel::SOAD);
    }

    public function testHandleWithEmptyPayload()
    {
        $this->expectException(PaymentGatewayException::class);
        $this->expectExceptionMessage('Unable to process empty callback payload');

        (new NotificationProcessor([]))->handle('', PaymentGatewayChannel::SOAD);
    }

    #[DataProvider('handleCorrectCallbackDataProvider')]
    public function testHandleCorrectCallback(
        string                $request,
        PaymentGatewayChannel $expectedChannel,
        string                $expectedDatetimeString,
        int                   $expectedAmount,
        TransactionStatus     $expectedStatus
    ) {
        $processor = new NotificationProcessor([
            new NirvanaNotificationHandler(),
            new RhcpNotificationHandler(),
            new SoadNotificationHandler()
        ]);

        $transaction = $processor->handle($request, $expectedChannel);

        $this->assertSame($expectedDatetimeString, $transaction->occurredAt()->format('Y-m-d H:i:s'));
        $this->assertSame($expectedAmount, $transaction->amount()->amount);
        $this->assertSame($expectedStatus, $transaction->status());
    }

    public static function handleCorrectCallbackDataProvider(): array
    {
        return [
            [
                file_get_contents(__DIR__ . '/stubs/nirvana.json'),
                PaymentGatewayChannel::NIRVANA,
                '2024-05-27 12:34:56',
                5000,
                TransactionStatus::PAID
            ],
            [
                file_get_contents(__DIR__ . '/stubs/soad.json'),
                PaymentGatewayChannel::SOAD,
                '2024-06-15 09:45:30',
                7500,
                TransactionStatus::PAID
            ],
            [
                file_get_contents(__DIR__ . '/stubs/rhcp.xml'),
                PaymentGatewayChannel::RHCP,
                '2024-07-10 14:20:45',
                9000,
                TransactionStatus::PAID
            ],
        ];
    }
}
