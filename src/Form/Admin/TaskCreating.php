<?php

declare(strict_types = 1);

namespace App\Form\Admin;


use App\Entity\Task;
use App\Repository\TasktypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskCreating extends AbstractType
{
    private $tasktypeRepository;

    public function __construct(TasktypeRepository $tasktypeRepository)
    {
        $this->tasktypeRepository = $tasktypeRepository;
    }

    private function getTasktypes(): array
    {
        $tasktypes["(no topic)"] = -1;
        foreach ($this->tasktypeRepository->findAll() as $tasktype) {
            $tasktypes[$tasktype->getName()] = $tasktype->getId();
        }
        return $tasktypes;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("description", TextareaType::class)
            ->add("period", TextType::class)
            ->add("plainTasktypeId", ChoiceType::class, [
                "choices" => [
                    $this->getTasktypes(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}