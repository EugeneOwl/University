<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Sprintstatus;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Usergroup;
use App\Entity\TaskType;
use App\Form\Admin\SprintstatusCreating;
use App\Form\Admin\TaskAndUserBinding;
use App\Form\Admin\TaskCreating;
use App\Form\Admin\TasktypeCreating;
use App\Form\Admin\UsergroupCreating;
use App\Service\UserInfoProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    private const COLLISION_WARNING = "Entity with this name does already exist.";
    private const CREATION_MESSAGE = "Entity created.";

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
    public function newUsergroup(Request $request): Response
    {
        $usergroup = new Usergroup();
        $usergroupRepository = $this->getDoctrine()->getRepository(Usergroup::class);
        $form = $this->createForm(UsergroupCreating::class, $usergroup);

        $doesUsergroupExist = false;
        $form->handleRequest($request);
        if (
            $form->isSubmitted() &&
            $form->isValid() &&
            !empty(trim((string)($usergroup->getName()))) &&
            ($doesUsergroupExist = $usergroupRepository->doesUsergroupExist($usergroup->getName())) === false
        ) {
            $doctrineManager = $this->getDoctrine()->getManager();
            $doctrineManager->persist($usergroup);
            $doctrineManager->flush();
            echo self::CREATION_MESSAGE;
        }

        return $this->render("admin/new/adminNewUsergroup.html.twig", [
            "title"  => "new user group",
            "header" => "Create new user group!",
            "form"   => $form->createView(),
            "collisionMessage" => $doesUsergroupExist ? self::COLLISION_WARNING : "",
            "usergroups" => $usergroupRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new/tasktype", name="app_admin_new_tasktype")
     */
    public function newTasktype(Request $request): Response
    {
        $tasktype = new TaskType();
        $tasktypeRepository = $this->getDoctrine()->getRepository(TaskType::class);
        $form = $this->createForm(TasktypeCreating::class, $tasktype);

        $doesTasktypeExist = false;
        $form->handleRequest($request);
        if (
            $form->isSubmitted() &&
            $form->isValid() &&
            !empty(trim((string)($tasktype->getName()))) &&
            ($doesTasktypeExist = $tasktypeRepository->doesTaskTypeExist($tasktype->getName())) === false
        ) {
            $doctrineManager = $this->getDoctrine()->getManager();
            $doctrineManager->persist($tasktype);
            $doctrineManager->flush();
            echo self::CREATION_MESSAGE;
        }
        return $this->render("admin/new/adminNewTasktype.html.twig", [
            "title"            => "new task type",
            "header"           => "Create new task type!",
            "form"             => $form->createView(),
            "collisionMessage" => $doesTasktypeExist ? self::COLLISION_WARNING : "",
            "tasktypes"        => $tasktypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new/task", name="app_admin_new_task")
     */
    public function newTask(Request $request): Response
    {
        $task = new Task();
        $taskRepository = $this->getDoctrine()->getRepository(Task::class);
        $form = $this->createForm(TaskCreating::class, $task);
        $doesTaskExist = false;
        $isPeriodCorrect = true;

        $form->handleRequest($request);
        if (
            $form->isSubmitted() &&
            $form->isValid() &&
            !empty(trim((string)($task->getDescription()))) &&
            ($isPeriodCorrect = ctype_digit(trim((string)$task->getPeriod()))) === true &&
            ($doesTaskExist = $taskRepository->doesTaskExist($task->getDescription())) === false
        ) {
            $doctrineManager = $this->getDoctrine()->getManager();
            if ($task->getPlainTasktypeId() > 0) {
                $task->setTasktype($doctrineManager->getRepository(TaskType::class)->find($task->getPlainTasktypeId()));
            }
            $doctrineManager->persist($task);
            $doctrineManager->flush();
            echo self::CREATION_MESSAGE;
        }
        return $this->render("admin/new/adminNewTask.html.twig", [
            "title"            => "new task",
            "header"           => "Create new task!",
            "form"             => $form->createView(),
            "collisionMessage" => $doesTaskExist ? self::COLLISION_WARNING : "",
            "periodErrorMessage" => $isPeriodCorrect ? "" : "Period should be number! (amount of days)",
            "tasks"            => $taskRepository->findAllOrderedByPeriod(),
        ]);
    }

    /**
     * @Route("/admin/new/sprintstatus", name="app_admin_new_sprintstatus")
     */
    public function newSprintstatus(Request $request): Response
    {
        $status = new Sprintstatus();
        $statusRepository = $this->getDoctrine()->getRepository(Sprintstatus::class);
        $form = $this->createForm(SprintstatusCreating::class, $status);

        $doesStatusExist = false;
        $form->handleRequest($request);
        if (
            $form->isSubmitted() &&
            $form->isValid() &&
            !empty(trim((string)($status->getName()))) &&
            ($doesStatusExist = $statusRepository->doesTaskTypeExist($status->getName())) === false
        ) {
            $doctrineManager = $this->getDoctrine()->getManager();
            $doctrineManager->persist($status);
            $doctrineManager->flush();
            echo self::CREATION_MESSAGE;
        }
        return $this->render("admin/new/adminNewSprintstatus.html.twig", [
            "title"            => "new task type",
            "header"           => "Create new task type!",
            "form"             => $form->createView(),
            "collisionMessage" => $doesStatusExist ? self::COLLISION_WARNING : "",
            "statuses"        => $statusRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/bind/taskAndUser", name="app_admin_bind_task_and_user")
     */
    public function bindTaskAndUser(Request $request, UserInfoProvider $userInfoProvider): Response
    {
        $task = new Task();
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $taskRepository = $this->getDoctrine()->getRepository(Task::class);
        $form = $this->createForm(TaskAndUserBinding::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $docrineManager = $this->getDoctrine()->getManager();

            $user = $userRepository->find($task->getPlainUsers());
            $task = $taskRepository->find($task->getPlainTasks());

            if (!$userInfoProvider->doesUserOwnTask($user->getUsername(), $task->getDescription())) {
                $task->addUser($user);
                $user->addTask($task);

                $docrineManager->persist($user);
                $docrineManager->persist($task);
                $docrineManager->flush();
                echo "Now user '{$user->getUsername()}' has task '{$task->getDescription()}'.";
            } else {
                echo "The user already has this task.";
            }
        }
        return $this->render("admin/bind/adminBindTaskAndUser.html.twig", [
            "title"  => "Bind task and user",
            "header" => "Bind task to user!",
            "users"  => $userInfoProvider->getUsersWithTasks(),
            "form"   => $form->createView(),
        ]);
    }
}