<?php

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\AttributeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('field_name', TextType::class, options: array(
                'label' => 'Enter field name',
            ))
//            ->add('userCollections')
//            ->add('type', EntityType::class, options: array(
//                'label' => 'Select field type',
//                'class' => AttributeType::class,
//                'choice_label' => 'type',
//                'placeholder' => '...'
//            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attribute::class,
        ]);
    }
}
