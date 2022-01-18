<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', type: TextType::class, options: array(
                'label' => 'Enter name',
            ))
            ->add('email', type: EmailType::class,options: array(
                'label' => 'Enter Email',
            ))
            ->add('roles', ChoiceType::class, options: array(
                'label' => 'Enter role',
                'choices' => ['ADMIN' => 'ROLE_ADMIN', 'USER' => 'ROLE_USER'],
            ))
            ->add('blocked', ChoiceType::class, options: array(
                'label' => 'Lock status',
                'choices' => ['Block' => true, 'Unblock' => false],
            ))
            ->add('save', type: SubmitType::class, options: array(
                'label' => 'Save'))
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) ? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    return [$rolesString];
                }
            ));
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
