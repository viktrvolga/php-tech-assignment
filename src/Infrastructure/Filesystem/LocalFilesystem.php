<?php

declare(strict_types=1);

namespace App\Infrastructure\Filesystem;

use App\Infrastructure\Filesystem\Exception\FilesystemException;

final readonly class LocalFilesystem implements Filesystem
{
    /** @var non-empty-string */
    private string $directory;

    public function __construct(string $directory)
    {
        if ($directory === '' || is_dir($directory) === false) {
            throw new FilesystemException(
                sprintf('Directory `%s` does not exist', $directory)
            );
        }

        if (is_readable($directory) === false || is_writable($directory) === false) {
            throw new FilesystemException(
                sprintf('Directory `%s` must be readable and writable', $directory)
            );
        }

        $this->directory = $directory;
    }

    public function directory(): string
    {
        return $this->directory;
    }

    public function delete(string $fileName): void
    {
        @unlink($this->buildFilePath($fileName));
    }

    public function has(string $fileName): bool
    {
        return file_exists($this->buildFilePath($fileName));
    }

    public function saveCsv(string $fileName, array $headers, array $rows, string $delimiter): void
    {
        $stream = new \SplFileObject($this->buildFilePath($fileName), 'c+');

        try {
            $stream->fputcsv($headers, $delimiter);

            foreach ($rows as $row) {
                $stream->fputcsv($row, $delimiter);
            }
        } catch (\Throwable $exception) {
            $this->delete($fileName);

            throw new FilesystemException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }

    private function buildFilePath(string $fileName): string
    {
        return sprintf('%s/%s', rtrim($this->directory, '/'), $fileName);
    }
}
