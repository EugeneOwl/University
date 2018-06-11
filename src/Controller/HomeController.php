<?php

declare(strict_types = 1);

namespace App\Controller;


use App\Entity\User;
use App\Entity\Usergroup;
use App\Service\UserInfoProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function run(UserInfoProvider $userInfoProvider): Response
    {
        $usergroupRepository = $this->getDoctrine()->getRepository(Usergroup::class);
        $usergroup = $usergroupRepository->findByUser($this->getUser());

        $userSprintTasks = $userInfoProvider->getUserSprintTasks($this->getUser());
        $role = $this->getUser()->getRoles()[0];
        return $this->render("home.html.twig", [
            "title"           => "home",
            "header"          => "Welcome, {$this->getUser()->getUsername()}",
            "subheader"       => $usergroup ? "from {$usergroup->getName()} group" : "(no user group)",
            "roleNote"        => "($role security level)",
            "tasks"           => $this->getUser()->getTasks(),
            "sprintTasks" => $userSprintTasks,
        ]);
    }
}