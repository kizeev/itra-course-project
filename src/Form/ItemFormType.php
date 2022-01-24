<?php

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Item;
use App\Entity\UserCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, options: array(
                'label' => 'Enter item name',
            ))
//            ->add('tag')
            ->add('item_values',CollectionType::class, options: array(
                'entry_type' => ValueFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ))
            ->add('attributes', CollectionType::class, options: array(
                'entry_type' => Attribute::class,
                'entry_options' => ['label' => false]
            ))
            ->add('save', type: SubmitType::class, options: array(
                'label' => 'Save',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'attributes' => array(),
        ]);
    }
}
