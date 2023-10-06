<?php

namespace App\Repository;

use App\Traits\Repository\CRUD;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseRepository extends ServiceEntityRepository
{
    public const ARRAY_RESULT = 'array';
    public const ENTITY_RESULT = 'result';
    public const EXECUTE = 'execute';
    public const ONE_OR_NULL_RESULT = 'one-or-null';
    public const SINGLE_SCALAR_RESULT = 'single-scalar';
    public const SINGLE_COLUMN_RESULT = 'single-column';

    use CRUD;
}