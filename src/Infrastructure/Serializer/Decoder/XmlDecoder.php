<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\Decoder;

use App\Infrastructure\Serializer\Exceptions\SerializerException;
use App\Infrastructure\Serializer\SerializerFormat;

/**
 * This is a very simplified example of reading XML, which is sufficient for the context of the task.
 * In reality, we will need to consider attributes, namespaces, external entities, and so on...
 */
final readonly class XmlDecoder implements SerializerDecoder
{
    public function supports(SerializerFormat $format): bool
    {
        return $format === SerializerFormat::XML;
    }

    public function decode(string $payload): array
    {
        $useErrors = libxml_use_internal_errors(true);

        libxml_clear_errors();
        $element = simplexml_load_string($payload);

        try {
            if ($element !== false) {
                return $this->read($element);
            }

            throw new SerializerException('The passed xml is invalid');
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($useErrors);
        }
    }

    /**
     * @param \SimpleXMLElement $element
     * @return array<string, mixed>
     */
    private function read(\SimpleXMLElement $element): array
    {
        $result = [];

        foreach ($element->children() as $element) {
            $name = $element->getName();
            $value = (string)$element;

            if ($element->count() > 0) {
                $result[$name] = $this->read($element);
            } else {
                $result[$name] = $value;
            }
        }

        return $result;
    }
}
