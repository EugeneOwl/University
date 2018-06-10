<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Sprint;
use App\Entity\Sprintstatus;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Usergroup;
use App\Entity\TaskType;
use App\Form\Admin\SprintAndTaskBinding;
use App\Form\Admin\SprintAndUserBinding;
use App\Form\Admin\SprintAndUsergroupBinding;
use App\Form\Admin\SprintCreating;
use App\Form\Admin\SprintstatusCreating;
use App\Form\Admin\TaskAndUserBinding;
use App\Form\Admin\TaskCreating;
use App\Form\Admin\TaskEditing;
use App\Form\Admin\TasktypeCreating;
use App\Form\Admin\UsergroupCreating;
use App\Service\SprintInfoProvider;
use App\Service\UserInfoProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    private const COLLISION_WARNING = "Entity with this name does already exist.";
    private const CREATION_MESSAGE = "Entity created.";
    private const UPDATING_MESSAGE = "Entities updated.";
    private const ALREADY_BINDED_MESSAGE = "This entities are already binded.";

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
            !($doesUsergroupExist = $usergroupRepository->doesUsergroupExist($usergroup->getName()))
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
            !($doesTasktypeExist = $tasktypeRepository->doesTaskTypeExist($tasktype->getName()))
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
            ($isPeriodCorrect = ctype_digit(trim((string)$task->getPeriod()))) &&
            !($doesTaskExist = $taskRepository->doesTaskExist($task->getDescription()))
        ) {
            $task->setIsDone(false);
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
            !($doesStatusExist = $statusRepository->doesTaskTypeExist($status->getName()))
        ) {
            $doctrineManager = $this->getDoctrine()->getManager();
            $doctrineManager->persist($status);
            $doctrineManager->flush();
            echo self::CREATION_MESSAGE;
        }
        return $this->render("admin/new/adminNewSprintstatus.html.twig", [
            "title"            => "new sprint status",
            "header"           => "Create new sprint status!",
            "form"             => $form->createView(),
            "collisionMessage" => $doesStatusExist ? self::COLLISION_WARNING : "",
            "statuses"        => $statusRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new/sprint", name="app_admin_new_sprint")
     */
    public function newSprint(Request $request): Response
    {
        $sprint = new Sprint();
        $sprintRepository = $this->getDoctrine()->getRepository(Sprint::class);
        $form = $this->createForm(SprintCreating::class, $sprint);

        $doesSprintExist = false;
        $form->handleRequest($request);
        if (
            $form->isSubmitted() &&
            $form->isValid() &&
            !($doesSprintExist = $sprintRepository->doesSprintExist($sprint->getName()))
        ) {
            $status = $this->getDoctrine()->getRepository(Sprintstatus::class)->find($sprint->getPlainStatus());
            $sprint->setStatus($status);
            $this->getDoctrine()->getManager()->persist($sprint);
            $this->getDoctrine()->getManager()->flush();
            echo self::CREATION_MESSAGE;
        }
        return $this->render("admin/new/adminNewSprint.html.twig", [
            "title"            => "new sprint",
            "header"           => "Create new sprint!",
            "form"             => $form->createView(),
            "sprints"          => $sprintRepository->findAll(),
            "collisionMessage" => $doesSprintExist ? self::COLLISION_WARNING : "",
        ]);
    }

    /**
     * @Route("/admin/edit/task", name="app_admin_edit_task")
     */
    public function editTask(Request $request): Response
    {
        $tasks = new Task();
        $form = $this->createForm(TaskEditing::class, $tasks);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedAmount = $this->getDoctrine()->getRepository(Task::class)->executeByIds(
                TaskEditing::getTasks(),
                $tasks->getPlainTasks()
            );
            echo $updatedAmount . " " . self::UPDATING_MESSAGE;
        }
        return $this->render("admin/edit/adminEditTask.html.twig", [
            "title"            => "update tasks",
            "header"           => "Update tasks!",
            "form"             => $form->createView(),
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
                echo self::ALREADY_BINDED_MESSAGE;
            }
        }
        return $this->render("admin/bind/adminBindTaskAndUser.html.twig", [
            "title"  => "Bind task and user",
            "header" => "Bind task to user!",
            "users"  => $userInfoProvider->getUsersWithTasks(),
            "form"   => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/bind/sprintAndTask", name="app_admin_bind_sprint_and_task")
     */
    public function bindSprintAndTask(Request $request, SprintInfoProvider $sprintInfoProvider): Response
    {
        $sprint = new Sprint();
        $sprintRepository = $this->getDoctrine()->getRepository(Sprint::class);
        $taskRepository = $this->getDoctrine()->getRepository(Task::class);
        $form = $this->createForm(SprintAndTaskBinding::class, $sprint);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $taskRepository->find($sprint->getPlainTask());
            $sprint = $sprintRepository->find($sprint->getPlainSprint());

            if (!$sprintInfoProvider->doesSprintOwnTask($sprint, $task)) {
                $sprint->addTask($task);
                $this->getDoctrine()->getManager()->persist($sprint);
                $this->getDoctrine()->getManager()->flush();
                echo "Now sprint '{$sprint->getName()}' has task '{$task->getDescription()}'.";
            } else {
                echo self::ALREADY_BINDED_MESSAGE;
            }
        }
        return $this->render("admin/bind/adminBindSprintAndTask.html.twig", [
            "title"  => "Bind task to sprint",
            "header" => "Bind task to sprint!",
            "sprints"  => $sprintRepository->findAll(),
            "form"   => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/bind/sprintAndUser", name="app_admin_bind_sprint_and_user")
     */
    public function bindSprintAndUser(Request $request, SprintInfoProvider $sprintInfoProvider): Response
    {
        $sprint = new Sprint();
        $sprintRepository = $this->getDoctrine()->getRepository(Sprint::class);
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $form = $this->createForm(SprintAndUserBinding::class, $sprint);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->find($sprint->getPlainUser());
            $sprint = $sprintRepository->find($sprint->getPlainSprint());

            if (!$sprintInfoProvider->doesSprintOwnUser($sprint, $user)) {
                $sprint->addUser($user);
                $this->getDoctrine()->getManager()->persist($sprint);
                $this->getDoctrine()->getManager()->flush();
                echo "Now sprint '{$sprint->getName()}' has user '{$user->getUsername()}'.";
            } else {
                echo self::ALREADY_BINDED_MESSAGE;
            }
        }
        return $this->render("admin/bind/adminBindSprintAndUser.html.twig", [
            "title"    => "Bind user to sprint",
            "header"   => "Bind user to sprint!",
            "sprints"  => $sprintRepository->findAll(),
            "form"     => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/bind/sprintAndUsergroup", name="app_admin_bind_sprint_and_usergroup")
     */
    public function bindSprintAndUsergroup(Request $request, SprintInfoProvider $sprintInfoProvider): Response
    {
        $sprint = new Sprint();
        $sprintRepository = $this->getDoctrine()->getRepository(Sprint::class);
        $usergroupRepository = $this->getDoctrine()->getRepository(Usergroup::class);
        $form = $this->createForm(SprintAndUsergroupBinding::class, $sprint);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $usergroup = $usergroupRepository->find($sprint->getPlainUsergroup());
            $sprint = $sprintRepository->find($sprint->getPlainSprint());

            if (!$sprintInfoProvider->doesSprintOwnUsergroup($sprint, $usergroup)) {
                $sprint->addUsergroup($usergroup);
                $this->getDoctrine()->getManager()->persist($sprint);
                $this->getDoctrine()->getManager()->flush();
                echo "Now sprint '{$sprint->getName()}' has user group '{$usergroup->getName()}'.";
            } else {
                echo self::ALREADY_BINDED_MESSAGE;
            }
        }
        return $this->render("admin/bind/adminBindSprintAndUsergroup.html.twig", [
            "title"    => "Bind usergroup to sprint",
            "header"   => "Bind usergroup to sprint!",
            "sprints"  => $sprintRepository->findAll(),
            "form"     => $form->createView(),
        ]);
    }
}