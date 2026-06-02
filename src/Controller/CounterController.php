<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CounterController extends AbstractController
{
    public function showCounters(): Response
    {
        return $this->render('layout/sidebar.html.twig');
    }
}
