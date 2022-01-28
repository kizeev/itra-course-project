<?php

namespace App\Form;

use App\Entity\Item;
use App\Form\DataTransformer\TagsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemFormType extends AbstractType
{
    private TagsTransformer $transformer;

    public function __construct(TagsTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, options: array(
                'label' => 'Enter item name',
            ))
            ->add('tags', CollectionType::class, options: array(
                'entry_type' => TagFormType::class,
                'entry_options' => [],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false
            ))
            ->add('item_values',CollectionType::class, options: array(
                'entry_type' => ValueFormType::class,
                'entry_options' => [],
                'allow_add' => true,
            ))
            ->add('save', type: SubmitType::class, options: array(
                'label' => 'Save',
            ))
        ;

        $builder->get('tags')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
