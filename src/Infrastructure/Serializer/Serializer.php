<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use App\Infrastructure\Serializer\Exceptions\SerializerException;

/**
 * Normally I would use SymfonySerializer, or JMS.
 * But since we are not allowed to use any third-party dependencies, I will make a simple analogue of them.
 */
interface Serializer
{
    /**
     * Here we will use generics, the support of which is provided to us by phpstan/psalm.
     * Thus, our static analysis will understand what specific data type the code works with.
     *
     * @template T of object
     *
     * @param string $payload
     * @param class-string<T> $to What class object should the payload be deserialized into
     * @param SerializerFormat $format
     *
     * @return T
     *
     * @throws SerializerException
     */
    public function unserialize(string $payload, string $to, SerializerFormat $format): object;
}
