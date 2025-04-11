<?php
namespace App\Form;

use App\Entity\Reference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ReferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,
array $options)
    {
        $builder
            ->add('numero',NumberType::class,array(
                'label'=>'N° de référence'
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reference::class,
        ]);
    }
}