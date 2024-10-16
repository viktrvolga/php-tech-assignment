<?php

declare(strict_types=1);

namespace App\PaymentReport;

use App\PaymentGateway\PaymentGatewayTransaction;
use App\PaymentReport\Data\TransactionReportEntry;
use App\PaymentReport\Writer\Exception\PaymentReportWriterException;
use App\PaymentReport\Writer\PaymentReportWriter;

final class PaymentReportGenerator
{
    public function __construct(
        private readonly PaymentReportWriter $writer
    ) {
    }

    /**
     * @param array<array-key, PaymentGatewayTransaction> $transactionCollection
     * @param bool $force Should we overwrite the existing file? If not, then if the file already exists, an
     *                     exception will be thrown.
     * @return void
     *
     * @throws PaymentReportWriterException
     */
    public function generate(array $transactionCollection, bool $force = false): void
    {
        $entries = \array_map(
            static function (PaymentGatewayTransaction $transaction): TransactionReportEntry {
                return TransactionReportEntry::fromTransaction($transaction);
            },
            $transactionCollection
        );

        $this->writer->generate(
            headers: $this->headers(),
            entries: $entries,
            force: $force
        );
    }

    /**
     * @return non-empty-array<int, non-empty-string>
     */
    final protected function headers(): array
    {
        $headers = [];
        $reflectionClass = new \ReflectionClass(TransactionReportEntry::class);

        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            /** @var non-empty-string $propertyName */
            $propertyName = strtolower(
                (string)preg_replace('/([a-z])([A-Z])/', '$1_$2', $property->name)
            );

            $headers[] = $propertyName;
        }

        // @codeCoverageIgnoreStart
        if (\count($headers) === 0) {
            throw new PaymentReportWriterException('Unable to collect headers from empty data class');
        }
        // @codeCoverageIgnoreEnd

        return $headers;
    }
}
