<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Serializer\Decoder;

use App\Infrastructure\Serializer\Decoder\XmlDecoder;
use App\Infrastructure\Serializer\Exceptions\SerializerException;
use PHPUnit\Framework\TestCase;

final class XmlDecoderTest extends TestCase
{
    public function testDecodeNoneXmlString()
    {
        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('The passed xml is invalid');

        (new XmlDecoder())->decode(__METHOD__);
    }

    public function testDecodeEmptyXml()
    {
        $this->assertSame([], (new XmlDecoder())->decode('<xml></xml>'));
    }

    public function testDecode()
    {
        $data = ['key' => 'value'];

        $this->assertSame($data, (new XmlDecoder())->decode('<xml><key>value</key></xml>'));
    }
}
