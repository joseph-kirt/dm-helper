<?php

namespace App\Controller;

use App\Repository\PlayerClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PlayerClassesController extends AbstractController
{
    private PlayerClassRepository $playerClassRepository;

    public function __construct(PlayerClassRepository $playerClassRepository)
    {
        $this->playerClassRepository = $playerClassRepository;
    }

    public function index(): Response
    {
        $playerClasses = $this->playerClassRepository->findAll();
        return $this->render('player-classes/index.html.twig', ['player_classes' => $playerClasses]);
    }
}