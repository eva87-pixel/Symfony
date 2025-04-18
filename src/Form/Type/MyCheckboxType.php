<?php

namespace App\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class MyCheckboxType
 *
 * Ce type de formulaire personnalisé étend le type CheckboxType de Symfony,
 * en ajoutant des attributs par défaut pour la balise HTML et pour l'étiquette.
 *
 * @package App\Form\Type
 */
class MyCheckboxType extends AbstractType
{
    /**
     * Configure les options par défaut pour ce type de champ.
     *
     * Définit les attributs HTML par défaut pour le champ (classe "cPerso")
     * et pour le label (classe "cEtiquette").
     *
     * @param OptionsResolver $resolver Le résolveur d'options
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'cPerso'],
            'label_attr' => ['class' => 'cEtiquette'],
        ]);
    }

    /**
     * Retourne le type parent dont hérite ce type personnalisé.
     *
     * Ici, il retourne CheckboxType::class afin d'étendre le type de case à cocher standard.
     *
     * @return string
     */
    public function getParent(): string
    {
        return CheckboxType::class;
    }
}