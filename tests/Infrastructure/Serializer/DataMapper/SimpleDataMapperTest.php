<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Serializer\DataMapper;

use App\Infrastructure\Serializer\DataMapper\SimpleDataMapper;
use App\Infrastructure\Serializer\Exceptions\SerializerException;
use PHPUnit\Framework\TestCase;
use Tests\Infrastructure\Serializer\DataMapper\Structures\ComplexStructure;
use Tests\Infrastructure\Serializer\DataMapper\Structures\EmptyStructure;
use Tests\Infrastructure\Serializer\DataMapper\Structures\SimpleStructure;
use Tests\Infrastructure\Serializer\DataMapper\Structures\SimpleStructureWithUnionType;

final class SimpleDataMapperTest extends TestCase
{
    public function testMapToStructureWithoutConstructor()
    {
        $this->expectException(SerializerException::class);

        (new SimpleDataMapper())->transform(['key' => 'value'], EmptyStructure::class);
    }

    public function testMapToSimpleStructure()
    {
        $result = (new SimpleDataMapper())->transform(['key' => 'value'], SimpleStructure::class);

        $this->assertInstanceOf(SimpleStructure::class, $result);
        $this->assertSame('value', $result->key);
    }

    public function testMapComplexStructure()
    {
        $now = date('Y-m-d H:i:s');
        $payload = [
            'datetime' => $now,
            'id' => '123123',
            'amount' => 100,
            'percent' => 50.4,
            'is_correct' => false,
            'details' => [
                'message' => null
            ]
        ];

        $result = (new SimpleDataMapper())->transform($payload, ComplexStructure::class);

        $this->assertInstanceOf(ComplexStructure::class, $result);
        $this->assertSame($now, $result->datetime->format('Y-m-d H:i:s'));
        $this->assertSame(100, $result->amount);
        $this->assertSame('default', $result->status);
        $this->assertFalse($result->isCorrect);
        $this->assertNull($result->details->message);
    }

    public function testMapWithoutNecessaryFields()
    {
        $this->expectException(SerializerException::class);

        (new SimpleDataMapper())->transform(['qwerty' => 'root'], SimpleStructure::class);
    }

    public function testMapToUnknownClass()
    {
        $this->expectException(SerializerException::class);

        (new SimpleDataMapper())->transform(['qwerty' => 'root'], 'OOoops');
    }

    public function testMapWithUnionTypes()
    {
        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Implemented only named types');

        (new SimpleDataMapper())->transform(['qwerty' => 'root'], SimpleStructureWithUnionType::class);
    }
}
