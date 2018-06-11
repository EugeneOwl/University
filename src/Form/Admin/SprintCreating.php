<?php

declare(strict_types = 1);

namespace App\Form\Admin;


use App\Entity\Sprint;
use App\Repository\SprintstatusRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SprintCreating extends AbstractType
{
    private $sprintstatusRepository;

    public function __construct(SprintstatusRepository $sprintstatusRepository)
    {
        $this->sprintstatusRepository = $sprintstatusRepository;
    }

    private function getStatuses(): array
    {
        foreach ($this->sprintstatusRepository->findAll() as $status) {
            $statuses[$status->getName()] = $status->getId();
        }
        return $statuses;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class)
            ->add("plainStatus", ChoiceType::class, [
                "choices" => [
                    $this->getStatuses(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sprint::class,
        ]);
    }
}