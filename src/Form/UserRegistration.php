<?php

declare(strict_types = 1);

namespace App\Form;


use App\Entity\User;
use App\Entity\Usergroup;
use App\Repository\UsergroupRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserRegistration extends AbstractType
{
    private $usergroupRepository;

    public function __construct(UsergroupRepository $usergroupRepository)
    {
        $this->usergroupRepository = $usergroupRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add("plainUsergroupName", ChoiceType::class, [
                'choices'  => [
                    $this->getUsergroupNames()
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
        ;
    }

    private function getUsergroupNames(): array
    {
        foreach ($this->usergroupRepository->findAll() as $group) {
            $usergroups[$group->getName()] = $group->getId();
        }
        return $usergroups ?? [];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}