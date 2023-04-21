<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

class BookableController
{
    #[Route('/')]
    public function homepage()
    {
        die('Bookable');
    }
}