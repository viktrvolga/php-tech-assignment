<?php

declare(strict_types=1);

namespace App\PaymentReport\Writer;

use App\Infrastructure\Filesystem\Exception\FilesystemException;
use App\PaymentReport\Data\TransactionReportEntry;
use App\PaymentReport\Writer\Exception\PaymentReportWriterException;

interface PaymentReportWriter
{
    /**
     * @param non-empty-array<int, non-empty-string> $headers
     * @param array<array-key, TransactionReportEntry> $entries
     * @param bool $force Should we overwrite the existing file? If not, then if the file already exists, an
     *                    exception will be thrown.
     * @return void
     *
     * @throws PaymentReportWriterException
     * @throws FilesystemException
     */
    public function generate(array $headers, array $entries, bool $force): void;
}
