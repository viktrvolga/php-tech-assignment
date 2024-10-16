<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Serializer\DataMapper\Structures;

final class NestedObjectStructure
{
    public function __construct(
        public readonly ?string $message
    ) {

    }
}
