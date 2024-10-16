<?php

declare(strict_types=1);

namespace Tests\PaymentReport;

use App\Common\Data\Money;
use App\Infrastructure\Filesystem\Filesystem;
use App\Infrastructure\Filesystem\LocalFilesystem;
use App\PaymentGateway\Common\TransactionStatus;
use App\PaymentGateway\PaymentGatewayTransaction;
use App\PaymentReport\PaymentReportGenerator;
use App\PaymentReport\Writer\CsvReportWriter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PaymentReportGeneratorTest extends TestCase
{
    private static array $transactions;

    private Filesystem $filesystem;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$transactions = [
            self::createTransaction(
                '2024-05-27T12:34:56Z',
                new Money(5000, 'EUR'),
                TransactionStatus::PAID
            ),
            self::createTransaction(
                '2024-07-10T14:20:45Z',
                new Money(9000, 'EUR'),
                TransactionStatus::PAID
            ),
            self::createTransaction(
                '2024-06-15T09:45:30Z',
                new Money(7500, 'EUR'),
                TransactionStatus::PAID
            )
        ];

    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = new LocalFilesystem('/tmp');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->filesystem->delete('notifications.csv');
        $this->filesystem->delete('notifications.txt');
    }

    #[DataProvider('generateSuccessfulReportDataProvider')]
    public function testGenerateSuccessfulReport(
        string $expectedFileName,
        string $expectedDelimiter,
        string $expectedContent
    ) {
        $generator = new PaymentReportGenerator(
            new CsvReportWriter(
                filesystem: $this->filesystem,
                fileName: $expectedFileName,
                delimiter: $expectedDelimiter
            )
        );

        $generator->generate(self::$transactions, true);

        $this->assertTrue($this->filesystem->has($expectedFileName));
        $this->assertSame(trim($expectedContent), trim(file_get_contents('/tmp/' . $expectedFileName)));
    }

    public static function generateSuccessfulReportDataProvider(): array
    {
        return [
            [
                'notifications.csv',
                ',',
                <<<CSV
date_time,status,total
"2024-05-27 12:34:56",paid,5000
"2024-07-10 14:20:45",paid,9000
"2024-06-15 09:45:30",paid,7500
CSV
            ],
            [
                'notifications.txt',
                ' ',
                <<<TEXT
date_time status total
"2024-05-27 12:34:56" paid 5000
"2024-07-10 14:20:45" paid 9000
"2024-06-15 09:45:30" paid 7500
TEXT
            ]
        ];
    }

    private static function createTransaction(
        string            $datetime,
        Money             $amount,
        TransactionStatus $status
    ): PaymentGatewayTransaction {
        $transaction = self::createStub(PaymentGatewayTransaction::class);

        $transaction->method('occurredAt')->willReturn(new \DateTimeImmutable($datetime));
        $transaction->method('amount')->willReturn($amount);
        $transaction->method('status')->willReturn($status);

        return $transaction;
    }
}
