<?php

declare(strict_types=1);

namespace App\Infrastructure\Filesystem;

use App\PaymentReport\Writer\Exception\PaymentReportWriterException;

/**
 * A simple abstraction over the file system. For example, if in the future we wanted to upload these files to Amazon S3.
 */
interface Filesystem
{
    /**
     * Returns the current directory
     *
     * @return non-empty-string
     */
    public function directory(): string;

    /**
     * @param non-empty-string $fileName
     * @return void
     */
    public function delete(string $fileName): void;

    /**
     * @param non-empty-string $fileName
     * @return bool
     */
    public function has(string $fileName): bool;

    /**
     * Saves a csv file.
     * There may be a problem here: if the file is large, it is better to write line by line. But in this case, such a
     * solution will be sufficient.
     *
     * @param non-empty-string $fileName
     * @param array<array-key, string> $headers
     * @param array<array-key, array<array-key, string|int>> $rows
     * @param non-empty-string $delimiter
     * @return void
     *
     * @throws PaymentReportWriterException
     */
    public function saveCsv(string $fileName, array $headers, array $rows, string $delimiter): void;
}
