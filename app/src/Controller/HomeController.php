<?php

namespace App\Controller;

use App\Repository\PlayerClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    private PlayerClassRepository $playerClassRepository;

    public function __construct(PlayerClassRepository $playerClassRepository)
    {
        $this->playerClassRepository = $playerClassRepository;
    }

    public function index(): Response
    {
        $playerClasses = $this->playerClassRepository->findAll(true);
        return $this->render('home/index.html.twig', ['player_classes' => $playerClasses]);
    }
}