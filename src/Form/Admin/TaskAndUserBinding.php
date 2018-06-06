<?php

namespace App\Form\Admin;


use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskAndUserBinding extends AbstractType
{
    private $taskRepository;
    private $userRepository;

    public function __construct(TaskRepository $taskRepository, UserRepository $userRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("plainUsers", ChoiceType::class, [
                "choices" => [
                    $this->getUsers(),
                ],
            ])
            ->add("plainTasks", ChoiceType::class, [
                "choices" => [
                    $this->getTasks(),
                ],
            ])
        ;
    }

    private function getTasks(): array
    {
        foreach ($this->taskRepository->findAll() as $task) {
            $tasks[$task->getDescription()] = $task->getId();
        }
        return $tasks ?? [];
    }

    private function getUsers(): array
    {
        foreach ($this->userRepository->findAll() as $user) {
            $users[$user->getUsername()] = $user->getId();
        }
        return $users ?? [];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}