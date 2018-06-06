<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function run(): Response
    {
        return $this->render("home.html.twig", [
            "title"     => "home",
            "header"    => "Welcome, " . $this->getUser()->getUsername(),
            "usergroup" => $this->getUser()->getUsergroup()->getName(),
        ]);
    }
}