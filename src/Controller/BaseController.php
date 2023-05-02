<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    private array $stylesheets;

    public function __construct() {
        $this->stylesheets[] = 'base.css';
    }

    #[Route('/', name: 'base')]
    public function home(): Response
    {
        return $this->render('base.html.twig', [
            'stylesheets' => $this->stylesheets,
        ]);
    }

}