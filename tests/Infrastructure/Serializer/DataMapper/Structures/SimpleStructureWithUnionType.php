<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Serializer\DataMapper\Structures;

final class SimpleStructureWithUnionType
{
    public function __construct(
        public string|int $qwerty
    ) {

    }
}
