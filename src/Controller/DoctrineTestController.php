<?php

declare(strict_types = 1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Usergroup;
use App\Entity\User;
use App\Entity\Task;
use App\Entity\Tasktype;

class DoctrineTestController extends AbstractController
{
    /**
     * @Route("/test", name="app_doctrine_test")
     */
    public function run(): Response
    {
        $this->addUserToUsergroup();
        return new Response("Done.");
    }

    private function createUserToUsergroup()
    {

    }


    private function create()
    {
        $this->getDoctrine()->getRepository(User::class)->find(1);

        //$this->getDoctrine()->getManager()->flush();
    }

    private function add()
    {
        $tasktype = $this->getDoctrine()->getManager()->getRepository(Tasktype::class)->findOneBy(["id" => 1]);
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(["username" => "user"]);

        $newTask = new Task();
        $newTask->setPeriod(4);
        $newTask->setDescription("New task.");

        $newTask->setTasktype($tasktype);
        $tasktype->addTask($newTask);

        $user->addTask($newTask);
        $newTask->addUser($user);

        $this->getDoctrine()->getManager()->persist($newTask);
        //$this->getDoctrine()->getManager()->flush();
    }

    private function add2()
    {
        $usergroup = $this->getDoctrine()->getManager()->getRepository(Usergroup::class)->findOneBy(["id" => 1]);
        $task = $this->getDoctrine()->getManager()->getRepository(Task::class)->findOneBy(["id" => 1]);

        $user = new User();
        $user->setUsername("newUser");
        $user->setPassword("password2");

        $user->setUsergroup($usergroup);
        $usergroup->addUser($user);
        $user->addTask($task);
        $task->addUser($user);

        $this->getDoctrine()->getManager()->persist($user);
        //$this->getDoctrine()->getManager()->flush();
    }
}