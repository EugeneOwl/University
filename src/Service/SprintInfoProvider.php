<?php

declare(strict_types = 1);

namespace App\Service;


use App\Entity\Sprint;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Usergroup;
use App\Repository\SprintRepository;
use App\Repository\TaskRepository;

class SprintInfoProvider
{
    private $sprintRepository;

    public function __construct(SprintRepository $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    public function doesSprintOwnTask(Sprint $sprint, Task $task): bool
    {
        $tasks = $this->sprintRepository->findOneBy(["name" => $sprint->getName()])->getTasks();
        foreach ($tasks as $currentTask) {
            if ($currentTask->getDescription() === $task->getDescription()) {
                return true;
            }
        }
        return false;
    }

    public function doesSprintOwnUser(Sprint $sprint, User $user): bool
    {
        $users = $this->sprintRepository->findOneBy(["name" => $sprint->getName()])->getUsers();
        foreach ($users as $currentUser) {
            if ($currentUser->getUsername() === $user->getUsername()) {
                return true;
            }
        }
        return false;
    }

    public function doesSprintOwnUsergroup(Sprint $sprint, Usergroup $usergroup): bool
    {
        $usergroups = $this->sprintRepository->findOneBy(["name" => $sprint->getName()])->getUsergroups();
        foreach ($usergroups as $currentUsergroup) {
            if ($currentUsergroup->getName() === $usergroup->getName()) {
                return true;
            }
        }
        return false;
    }

    public function getTaskStatistics(Sprint $sprint): array
    {
        $tasks = $sprint->getTasks();
        $taskCount = count($tasks);
        $doneTasksCount = 0;
        foreach ($tasks as $task) {
            if ($task->getIsDone()) {
                $doneTasksCount++;
            }
        }
        return [$doneTasksCount, $taskCount];
    }

    public function getTaskStatistics2(Sprint $sprint): array
    {
        return [
            $this->sprintRepository->getExecutedTaskCount($sprint),
            $this->sprintRepository->getTaskCount($sprint),
        ];
    }

    public function getNotDoneTasksFromClosedSprints(string $statusClosed, TaskRepository $taskRepository): array
    {
        $rowTaskIds = $this->sprintRepository->getNotDoneTasksFromClosedSprints($statusClosed);
        foreach ($rowTaskIds as $sprint) {
            $notExecutedTaskIds[] = $sprint["id"];
        }
        foreach (array_unique($notExecutedTaskIds) as $notExecutedTaskId) {
            $notExecutedTasks[] = $taskRepository->find($notExecutedTaskId);
        }
        return $notExecutedTasks;
    }
}