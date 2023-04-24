<?php

namespace App\Form\Type;

use App\Dto\ManageUserPointDto;
use App\Manager\TaskManager;
use App\Manager\UserManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPointType extends AbstractType
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly TaskManager $taskManager,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', ChoiceType::class, [
                'label' => 'Пользователи',
                'choices' => $this->userManager->getAllUsersForChoice(),
            ])
            ->add('task', ChoiceType::class, [
                'label' => 'Задание',
                'choices' => $this->taskManager->getAllTasksForChoice(),
            ])
            ->add('points', IntegerType::class, [
                'label' => 'Баллы',
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ManageUserPointDto::class,
            'empty_data' => new ManageUserPointDto(),
        ]);
    }
}
