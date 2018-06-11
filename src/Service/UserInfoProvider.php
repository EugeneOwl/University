<?php

declare(strict_types = 1);

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;

class UserInfoProvider
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsersWithTasks(): array
    {
        foreach ($this->userRepository->findAll() as $user) {
            if (count($user->getTasks()) === 0) {
                $users[$user->getUsername()] = [];
            }
            foreach ($user->getTasks() as $task) {
                $users[$user->getUsername()][] = $task->getDescription();
            }
        }
        return $users ?? [];
    }

    public function doesUserOwnTask(string $username, string $taskDescription): bool
    {
        $tasks = $this->userRepository->findOneBy(["username" => $username])->getTasks();
        foreach ($tasks as $task) {
            if ($task->getDescription() === $taskDescription) {
                return true;
            }
        }
        return false;
    }

    public function getUserSprintTasks(User $user): array
    {
       $rawTasks = $this->userRepository->getUserSprintTasks($user);
       foreach ($rawTasks as $rawTask) {
            $tasks[$rawTask["name"]][$rawTask["description"]] = $rawTask["execution"];
       }
       return $tasks;
    }
}