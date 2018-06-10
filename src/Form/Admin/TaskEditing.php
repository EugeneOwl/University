<?php

declare(strict_types = 1);

namespace App\Form\Admin;


use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskEditing extends AbstractType
{
    private static $tasks = [];
    private static $executedTaskIds = [];

    public static function getTasks(): array
    {
        return self::$tasks ?? [];
    }

    public function __construct(TaskRepository $taskRepository)
    {
        $this->setTasks($taskRepository);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainTasks', ChoiceType::class, [
                'choices' => self::$tasks,
                'multiple' => true,
                'expanded' => true,
                "data" => self::$executedTaskIds,
            ])
        ;
    }

    private function setTasks(TaskRepository $taskRepository): void
    {
        foreach ($taskRepository->findAll() as $task) {
            self::$tasks[$task->getDescription()] = $task->getId();

            if ($task->getIsDone()) {
                self::$executedTaskIds[] = $task->getId();
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}