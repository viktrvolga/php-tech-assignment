<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use App\Infrastructure\Serializer\DataMapper\DataMapper;
use App\Infrastructure\Serializer\DataMapper\SimpleDataMapper;
use App\Infrastructure\Serializer\Decoder\JsonDecoder;
use App\Infrastructure\Serializer\Decoder\SerializerDecoder;
use App\Infrastructure\Serializer\Decoder\XmlDecoder;
use App\Infrastructure\Serializer\Exceptions\SerializerException;

final readonly class DefaultSerializer implements Serializer
{
    /**
     * @param SerializerDecoder[] $decoderCollection
     * @param DataMapper $dataMapper
     */
    public function __construct(
        private array      $decoderCollection = [
            new JsonDecoder(),
            new XmlDecoder()
        ],
        private DataMapper $dataMapper = new SimpleDataMapper()
    ) {
    }

    public function unserialize(string $payload, string $to, SerializerFormat $format): object
    {
        if ($payload !== '') {
            $decodedPayload = $this->decode($payload, $format);

            return $this->dataMapper->transform($decodedPayload, $to);
        }

        throw new SerializerException('Payload can\'t be empty');
    }

    /**
     * @param non-empty-string $payload
     * @param SerializerFormat $format
     *
     * @return array<string, mixed>
     */
    private function decode(string $payload, SerializerFormat $format): array
    {
        foreach ($this->decoderCollection as $decoder) {
            if ($decoder->supports($format)) {
                return $decoder->decode($payload);
            }
        }

        throw new SerializerException(\sprintf('Unable to find decoder for `%s` format', $format->name));
    }
}
