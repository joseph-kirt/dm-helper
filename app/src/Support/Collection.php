<?php

namespace App\Support;

use function array_map;
use function explode;
use function is_array;
use function str_contains;

class Collection extends \Illuminate\Support\Collection
{
    public function toArray($keys = null): array
    {
        if (is_array($keys)) {
            return array_map(static function (mixed $value) use ($keys) {
                $item = [];
                foreach ($keys as $key) {
                    if (isset($value[$key])) {
                        $item[$key] = $value[$key];
                        continue;
                    }
                    // Key wasn't found, explode on the period and do a deeper search
                    if (str_contains($key, '.')) {
                        $keyParts = explode('.', $key);
                        $valuePart = $value;
                        foreach ($keyParts as $keyPart) {
                            $valuePart = $valuePart[$keyPart] ?? null;
                        }
                        $item[$key] = $valuePart;
                    }
                }
                return $item;
            }, $this->items);
        }

        return parent::toArray();
    }
}