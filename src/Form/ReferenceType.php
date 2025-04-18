<?php

namespace App\Form;

use App\Entity\Reference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 * Class ReferenceType
 *
 * Ce type de formulaire est utilisé pour gérer les données de l'entité Reference.
 * Il définit un champ "numero" de type NumberType avec le libellé "N° de référence".
 *
 * @package App\Form
 */
class ReferenceType extends AbstractType
{
    /**
     * Configure la construction du formulaire pour l'entité Reference.
     *
     * Ajoute le champ "numero" de type NumberType.
     *
     * @param FormBuilderInterface $builder Le builder pour construire le formulaire.
     * @param array $options Un tableau d'options.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', NumberType::class, [
                'label' => 'N° de référence'
            ]);
    }

    /**
     * Configure les options du formulaire.
     *
     * Lie ce formulaire à l'entité Reference.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reference::class,
        ]);
    }
}