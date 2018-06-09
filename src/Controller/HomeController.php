<?php

declare(strict_types = 1);

namespace App\Controller;


use App\Entity\Usergroup;
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
        $usergroup = $this->getDoctrine()->getRepository(Usergroup::class)->getUsergroup($this->getUser()->getId())->getName();
        $role = $this->getUser()->getRoles()[0];
        return $this->render("home.html.twig", [
            "title"     => "home",
            "header"    => "Welcome, {$this->getUser()->getUsername()}",
            "subheader" => "from $usergroup ($role role)",
            "tasks"     => $this->getUser()->getTasks(),
        ]);
    }
}