<?php

declare(strict_types = 1);

namespace App\Form\Admin;


use App\Entity\Sprint;
use App\Repository\SprintRepository;
use App\Repository\TaskRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SprintAndTaskBinding extends AbstractType
{
    private $taskRepository;
    private $sprintRepository;

    public function __construct(TaskRepository $taskRepository, SprintRepository $sprintRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->sprintRepository = $sprintRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("plainSprint", ChoiceType::class, [
                "choices" => [
                    $this->getSprints(),
                ],
            ])
            ->add("plainTask", ChoiceType::class, [
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

    private function getSprints(): array
    {
        foreach ($this->sprintRepository->findAll() as $sprint) {
            $sprints[$sprint->getName()] = $sprint->getId();
        }
        return $sprints ?? [];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sprint::class,
        ]);
    }
}