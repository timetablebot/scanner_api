<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloIndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return new Response(
            '<!DOCTYPE HTML><html><body>Hey</body></html>',
            418 // I'm a teapod
        );
    }
}
