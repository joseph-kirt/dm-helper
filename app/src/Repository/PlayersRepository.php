<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Persistence\ManagerRegistry;

class PlayersRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }
}