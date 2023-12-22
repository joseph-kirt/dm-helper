<?php

namespace App\Entity;

use App\Traits\Entity\Arrayable;
use ArrayAccess;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JsonException;
use JsonSerializable;

#[ORM\MappedSuperclass]
abstract class BaseEntity implements ArrayAccess, JsonSerializable
{
    use Arrayable;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Serializes entity and returns it as an array, Retains null values
     */
    public function jsonSerialize(): array
    {
        $serializer = SerializerBuilder::create()->build();
        $data = $serializer->serialize($this, 'json', SerializationContext::create()->setSerializeNull(true));

        try {
            return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
        }

        return [];
    }
}