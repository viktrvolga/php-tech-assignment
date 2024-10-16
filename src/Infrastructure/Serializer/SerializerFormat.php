<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

enum SerializerFormat
{
    case XML;
    case JSON;
}
