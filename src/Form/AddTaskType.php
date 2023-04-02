<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AddTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Le nom de la tâche',
                'attr' => [
                    'placeholder' => 'Merci de saisir le nom de la tâche'
                ]
            ])
            ->add('description', TextType::class,[
                'label' => 'La description de la tâche',
                'attr' => [
                    'placeholder' => 'Merci de saisir la description de la tâche'
                ]
            ])
            ->add('date', DateType::class,[
                'label' => 'La date de la tâche',
                'format' => 'dd/MM/yyyy',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
