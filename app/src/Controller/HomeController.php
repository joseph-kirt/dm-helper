<?php

namespace App\Controller;

use App\Repository\PlayersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    private PlayersRepository $playersRepository;

    public function __construct(PlayersRepository $playersRepository)
    {
        $this->playersRepository = $playersRepository;
    }

    public function index(): Response
    {
        $players = $this->playersRepository->findAll();
        return $this->render('home/index.html.twig', ['players' => $players]);
    }
}