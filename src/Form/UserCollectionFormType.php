<?php

namespace App\Form;

use App\Entity\UserCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class UserCollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', type: TextType::class, options: array(
                'label' => 'Enter collection name',
            ))
            ->add('description', type: TextareaType::class, options: array(
                'label' => 'Describe your collection'
            ))
            ->add('image', type: FileType::class, options: array(
                'label' => 'Choose image',
                'required' => false,
                'mapped' => false,
            ))
            ->add('save', type: SubmitType::class, options: array(
                'label' => 'Save',
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserCollection::class,
        ]);
    }
}