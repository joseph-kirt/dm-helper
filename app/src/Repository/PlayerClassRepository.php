<?php

namespace App\Repository;

use App\Entity\PlayerClass;
use Doctrine\Persistence\ManagerRegistry;

class PlayerClassRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerClass::class);
    }
}