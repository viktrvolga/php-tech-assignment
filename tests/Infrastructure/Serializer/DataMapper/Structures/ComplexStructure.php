<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Serializer\DataMapper\Structures;

final class ComplexStructure
{
    public function __construct(
        public \DateTimeImmutable    $datetime,
        public string                $id,
        public int                   $amount,
        public float                 $percent,
        public bool                  $isCorrect,
        public NestedObjectStructure $details,
        public string                $status = 'default'
    ) {
    }
}
