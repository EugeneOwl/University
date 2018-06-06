<?php

declare(strict_types = 1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function run(): Response
    {
        return $this->render("admin/admin.html.twig", [
            "title"     => "Admin",
            "header"    => "Admin manager",
            "subheader" => "Chose option"
        ]);
    }

    /**
     * @Route("/admin/new/usergroup", name="app_admin_new_usergroup")
     */
    public function newUsergroup(): Response
    {
        return $this->render("admin/new/adminNewUsergroup.html.twig", [
            "title" => "new user group",
            "header" => "Create new user group!",
        ]);
    }

    /**
     * @Route("/admin/new/tasktype", name="app_admin_new_tasktype")
     */
    public function newTasktype(): Response
    {
        return $this->render("admin/new/adminNewTasktype.html.twig", [
            "title" => "new task type",
            "header" => "Create new task type!",
        ]);
    }

    /**
     * @Route("/admin/new/task", name="app_admin_new_task")
     */
    public function newTask(): Response
    {
        return $this->render("admin/new/adminNewTask.html.twig", [
            "title" => "new task",
            "header" => "Create new task!",
        ]);
    }

    /**
     * @Route("/admin/bind/taskAndUser", name="app_admin_bind_task_and_user")
     */
    public function bindTaskAndUser(): Response
    {
        return $this->render("admin/bind/adminBindTaskAndUser.html.twig", [
            "title" => "Bind task and user",
            "header" => "Bind task to user!",
        ]);
    }
}