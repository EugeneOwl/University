<?php

declare(strict_types = 1);

namespace App\Form\Admin;


use App\Entity\Sprint;
use App\Repository\SprintstatusRepository;
use App\Repository\SprintRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SprintEditing extends AbstractType
{
    private $statusRepository;
    private $sprintRepository;

    public function __construct(SprintRepository $sprintRepository, SprintstatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
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
            ->add("plainStatus", ChoiceType::class, [
                "choices" => [
                    $this->getUsers(),
                ],
            ])
        ;
    }

    private function getUsers(): array
    {
        foreach ($this->statusRepository->findAll() as $status) {
            $statuses[$status->getName()] = $status->getId();
        }
        return $statuses ?? [];
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