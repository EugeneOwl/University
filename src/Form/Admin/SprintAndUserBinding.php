<?php

declare(strict_types = 1);

namespace App\Form\Admin;


use App\Entity\Sprint;
use App\Repository\SprintRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SprintAndUserBinding extends AbstractType
{
    private $userRepository;
    private $sprintRepository;

    public function __construct(UserRepository $userRepository, SprintRepository $sprintRepository)
    {
        $this->userRepository = $userRepository;
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
            ->add("plainUser", ChoiceType::class, [
                "choices" => [
                    $this->getUsers(),
                ],
            ])
        ;
    }

    private function getUsers(): array
    {
        foreach ($this->userRepository->findAll() as $user) {
            $users[$user->getUsername()] = $user->getId();
        }
        return $users ?? [];
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