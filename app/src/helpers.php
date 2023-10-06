<?php

use App\Support\Collection;

function collection(?array $value = null): Collection
{
    return (new Collection($value));
}