<?php

declare(strict_types=1);

namespace Tests\PaymentReport\Writer;

use App\Infrastructure\Filesystem\Filesystem;
use App\PaymentReport\Writer\CsvReportWriter;
use App\PaymentReport\Writer\Exception\PaymentReportWriterException;
use PHPUnit\Framework\TestCase;

final class CsvReportWriterTest extends TestCase
{
    private Filesystem $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

        $mock = $this->createMock(Filesystem::class);
        $mock->method('directory')->willReturn('/tmp');
        $mock->method('has')->willReturn(true);

        $this->filesystem = $mock;
    }

    public function testCreateWithEmptyFilename()
    {
        $this->expectException(PaymentReportWriterException::class);
        $this->expectExceptionMessage('Result filename can\'t be empty');

        new CsvReportWriter($this->filesystem, '', ',');
    }

    public function testCreateWithEmptyDelimiter()
    {
        $this->expectException(PaymentReportWriterException::class);
        $this->expectExceptionMessage('Rows delimiter must be specified');

        new CsvReportWriter($this->filesystem, 'demo.txt', '');
    }

    public function testGenerateWhenFileAlreadyExists()
    {
        $this->expectException(PaymentReportWriterException::class);
        $this->expectExceptionMessage('File `demo.txt` already exists in `/tmp`');

        $writer = new CsvReportWriter($this->filesystem, 'demo.txt', ',');
        $writer->generate(['id'], [['1']], false);
    }
}
