<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Serializer;

use App\Infrastructure\Serializer\DataMapper\SimpleDataMapper;
use App\Infrastructure\Serializer\Decoder\JsonDecoder;
use App\Infrastructure\Serializer\Decoder\XmlDecoder;
use App\Infrastructure\Serializer\DefaultSerializer;
use App\Infrastructure\Serializer\Exceptions\SerializerException;
use App\Infrastructure\Serializer\SerializerFormat;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Infrastructure\Serializer\DataMapper\Structures\ComplexStructure;
use Tests\Infrastructure\Serializer\DataMapper\Structures\SimpleStructure;

final class DefaultSerializerTest extends TestCase
{
    private DefaultSerializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new DefaultSerializer(
            [
                new JsonDecoder(),
                new XmlDecoder()
            ],
            new SimpleDataMapper()
        );
    }

    #[DataProvider('unserializeEmptyStringDataProvider')]
    public function testUnserializeEmptyString(SerializerFormat $format)
    {
        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Payload can\'t be empty');

        $this->serializer->unserialize('', __CLASS__, $format);
    }

    public static function unserializeEmptyStringDataProvider(): array
    {
        return [
            [SerializerFormat::JSON],
            [SerializerFormat::XML]
        ];
    }

    public function testUnserializeWithUnsupportedFormat()
    {
        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Unable to find decoder for `JSON` format');

        $serializer = new DefaultSerializer(
            [new XmlDecoder()],
            new SimpleDataMapper()
        );

        $serializer->unserialize('{}', __CLASS__, SerializerFormat::JSON);
    }

    public function testSuccessfulUnserializeSimpleJson()
    {
        $result = $this->serializer->unserialize(
            '{"key":"value"}',
            SimpleStructure::class,
            SerializerFormat::JSON
        );

        $this->assertSame("value", $result->key);
    }

    public function testSuccessfulUnserializeComplexXml()
    {
        $xml = <<<XML
<root>
   <id>123</id>
   <datetime>2024-10-16 17:16:32</datetime>
   <amount>600</amount>
   <percent>10.2</percent>
   <is_correct>true</is_correct>
   <details>
      <message>test</message>
   </details>
</root>
XML;

        $result = $this->serializer->unserialize(
            $xml,
            ComplexStructure::class,
            SerializerFormat::XML
        );

        $this->assertSame(600, $result->amount);
        $this->assertSame("test", $result->details->message);
    }
}
