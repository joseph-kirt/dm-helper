<?php

namespace App\Traits\Entity;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use function method_exists;
use function property_exists;

trait Arrayable
{
    public function fill(array $attributes): void
    {
        foreach ($attributes as $attribute => $value) {
            $method = 'set' . Str::studly($attribute);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    public function newCollection(array $models = []): Collection
    {
        return new Collection($models);
    }

    public function getAttribute(string $key): mixed
    {
        if (!$key) {
            return null;
        }

        $studlyKey = Str::studly($key);
        $camelKey = Str::camel($key);

        // If the attribute has a "get" mutator we will get the attribute's value
        if ($this->hasGetMutator($studlyKey)) {
            return $this->callGetMutator($studlyKey);
        }

        if ($this->hasGetAccessor($studlyKey)) {
            return $this->callGetAccessor($studlyKey);
        }

        if ($this->isAccessor($studlyKey)) {
            return $this->callAccessor($studlyKey);
        }

        // Last effort, attempt to access property directly
        if (property_exists($this, $camelKey)) {
            return $this->$camelKey;
        }

        return null;
    }

    public function hasGetMutator(string $studlyKey): bool
    {
        return method_exists($this, 'get' . $studlyKey . 'Attribute');
    }

    public function callGetMutator(string $studlyKey): mixed
    {
        return $this->{'get' . $studlyKey . 'Attribute'}();
    }

    public function hasGetAccessor(string $studlyKey): bool
    {
        return method_exists($this, 'get' . $studlyKey);
    }

    public function callGetAccessor(string $studlyKey): mixed
    {
        return $this->{'get' . $studlyKey}();
    }

    public function isAccessor(string $studlyKey): bool
    {
        return method_exists($this, $studlyKey);
    }

    public function callAccessor(string $studlyKey): mixed
    {
        return $this->{$studlyKey}();
    }

    public function __get(string $key): mixed
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->{$key} = $value;
    }

    public function __isset($key): bool
    {
        $studlyKey = Str::studly($key);
        // property_exists returns false for doctrine proxies, check with method_exists instead
        return property_exists($this, Str::camel($key)) || $this->hasGetAccessor($studlyKey) || $this->hasGetMutator($studlyKey) || $this->isAccessor($studlyKey);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        $property = Str::camel($offset);
        return property_exists($this, $property);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        return $this->getAttribute($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $method = 'set' . Str::studly($offset);
        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } else {
            $this->$offset = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        $property = Str::camel($offset);
        unset($this->{$property});
    }
}