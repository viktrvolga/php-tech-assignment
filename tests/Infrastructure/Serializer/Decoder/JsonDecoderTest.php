<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Serializer\Decoder;

use App\Infrastructure\Serializer\Decoder\JsonDecoder;
use App\Infrastructure\Serializer\Exceptions\SerializerException;
use PHPUnit\Framework\TestCase;

final class JsonDecoderTest extends TestCase
{
    public function testDecodeNonJsonString()
    {
        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('The passed json is invalid');

        (new JsonDecoder())->decode(__METHOD__);
    }

    public function testDecodeEmptyJson()
    {
        $this->assertSame([], (new JsonDecoder())->decode('{}'));
    }

    public function testDecode()
    {
        $data = ['key' => 'value'];

        $this->assertSame($data, (new JsonDecoder())->decode(json_encode($data)));
    }
}
