<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CounterController extends AbstractController
{
    public function show(): Response
    {
        return $this->render('counter/show.html.twig');
    }
}
