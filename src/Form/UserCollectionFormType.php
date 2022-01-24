<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\UserCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ->add('category', type: EntityType::class, options: array(
                'label' => 'Select the category',
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '...'
            ))
            ->add('name', type: TextType::class, options: array(
                'label' => 'Enter collection name',
            ))
            ->add('description', type: TextareaType::class, options: array(
                'label' => 'Describe your collection',
                'required' => false
            ))
            ->add('image', type: FileType::class, options: array(
                'label' => 'Choose image',
                'required' => false,
                'mapped' => false,
            ))
            ->add('attribute', type: CollectionType::class, options: array(
                'entry_type' => AttributeFormType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Attributes',
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
