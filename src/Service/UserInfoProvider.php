<?php

declare(strict_types = 1);

namespace App\Service;


use App\Repository\TaskRepository;
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
}