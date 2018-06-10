<?php

declare(strict_types = 1);

namespace App\Form\Admin;


use App\Entity\Sprint;
use App\Repository\SprintRepository;
use App\Repository\UsergroupRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SprintAndUsergroupBinding extends AbstractType
{
    private $usergroupRepository;
    private $sprintRepository;

    public function __construct(UsergroupRepository $usergroupRepository, SprintRepository $sprintRepository)
    {
        $this->usergroupRepository = $usergroupRepository;
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
            ->add("plainUsergroup", ChoiceType::class, [
                "choices" => [
                    $this->getUsergroups(),
                ],
            ])
        ;
    }

    private function getUsergroups(): array
    {
        foreach ($this->usergroupRepository->findAll() as $usergroup) {
            $usergroups[$usergroup->getName()] = $usergroup->getId();
        }
        return $usergroups ?? [];
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