<?php

declare(strict_types=1);

namespace App\PaymentReport\Writer;

use App\Infrastructure\Filesystem\Filesystem;
use App\PaymentReport\Data\TransactionReportEntry;
use App\PaymentReport\Writer\Exception\PaymentReportWriterException;

final class CsvReportWriter implements PaymentReportWriter
{
    /** @var non-empty-string */
    private readonly string $fileName;

    /** @var non-empty-string */
    private readonly string $delimiter;

    public function __construct(
        private readonly Filesystem $filesystem,
        string                      $fileName,
        string                      $delimiter
    ) {
        if ($fileName === '') {
            throw new PaymentReportWriterException('Result filename can\'t be empty');
        }

        if ($delimiter === '') {
            throw new PaymentReportWriterException('Rows delimiter must be specified');
        }

        $this->fileName = $fileName;
        $this->delimiter = $delimiter;
    }

    public function generate(array $headers, array $entries, bool $force): void
    {
        if ($force) {
            $this->filesystem->delete($this->fileName);
        }

        if ($this->filesystem->has($this->fileName)) {
            throw new PaymentReportWriterException(
                sprintf('File `%s` already exists in `%s`', $this->fileName, $this->filesystem->directory())
            );
        }

        $rows = $this->transform($entries);

        $this->filesystem->saveCsv(
            fileName: $this->fileName,
            headers: $headers,
            rows: $rows,
            delimiter: $this->delimiter
        );
    }

    /**
     * @param array<array-key, TransactionReportEntry> $entries
     * @return array<array-key, array<array-key, string|int>>
     */
    private function transform(array $entries): array
    {
        return array_map(
            static function (TransactionReportEntry $entry): array {
                return [
                    $entry->dateTime->format('Y-m-d H:i:s'),
                    $entry->status,
                    $entry->total
                ];
            },
            $entries
        );
    }
}
