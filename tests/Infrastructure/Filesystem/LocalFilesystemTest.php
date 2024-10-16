<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Filesystem;

use App\Infrastructure\Filesystem\Exception\FilesystemException;
use App\Infrastructure\Filesystem\LocalFilesystem;
use PHPUnit\Framework\TestCase;

final class LocalFilesystemTest extends TestCase
{
    public function testCreateWithIncorrectDirectoryPath()
    {
        $this->expectException(FilesystemException::class);
        $this->expectExceptionMessage('Directory `/segwshgsegseg` does not exist');

        new LocalFilesystem('/segwshgsegseg');
    }

    public function testCreateWithEmptyDirectoryPath()
    {
        $this->expectException(FilesystemException::class);
        $this->expectExceptionMessage('Directory `` does not exist');

        new LocalFilesystem('');
    }

    public function testCreateWithNonReadableDirectoryPath()
    {
        $this->expectException(FilesystemException::class);
        $this->expectExceptionMessage('Directory `/root` must be readable and writable');

        new LocalFilesystem('/root');
    }

    public function testCreateWithCorrectDirectory()
    {
        $this->assertSame(__DIR__, (new LocalFilesystem(__DIR__))->directory());
    }

    public function testDeleteUnknownFile()
    {
        (new LocalFilesystem(__DIR__))->delete('non-existent-file.sh');

        $this->assertTrue(true);
    }

    public function testDeleteExistingFile()
    {
        $filesystem = new LocalFilesystem('/tmp');

        touch('/tmp/qwerty.sh');

        $this->assertTrue($filesystem->has('qwerty.sh'));
        $filesystem->delete('qwerty.sh');
        $this->assertFalse($filesystem->has('qwerty.sh'));
    }

    public function testSaveCorrectCsv()
    {
        $filesystem = new LocalFilesystem('/tmp');

        $filesystem->delete('demo.csv');
        $filesystem->saveCsv('demo.csv', ['id'], [['1']], ',');

        $this->assertTrue($filesystem->has('demo.csv'));

        $this->assertSame('id' . PHP_EOL . '1' . PHP_EOL, file_get_contents('/tmp/demo.csv'));

        $filesystem->delete('demo.csv');
    }

    public function testSaveWithIncorrectDelimiter()
    {
        $this->expectException(FilesystemException::class);

        $filesystem = new LocalFilesystem('/tmp');

        $filesystem->delete('demo.csv');
        $filesystem->saveCsv('demo.csv', ['id'], [['1']], '');
    }
}
