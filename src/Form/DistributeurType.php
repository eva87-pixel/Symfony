<?php

namespace App\Form;

use App\Entity\Distributeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DistributeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,
                 array $options)
    {
        $builder->add('nom',TextType::class,array(
            'label'=>'Nom du distributeur'
        )) ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Distributeur::class,
        ]);
    }
}