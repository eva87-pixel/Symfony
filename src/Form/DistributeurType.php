<?php

namespace App\Form;

use App\Entity\Distributeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class DistributeurType
 *
 * Ce type de formulaire est utilisé pour gérer les données de l'entité Distributeur.
 * Il définit un champ "nom" de type TextType qui permet d'entrer le nom du distributeur.
 *
 * @package App\Form
 */
class DistributeurType extends AbstractType
{
    /**
     * Construire le formulaire en ajoutant les champs nécessaires.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options passées au formulaire.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('nom', TextType::class, [
            'label' => 'Nom du distributeur'
        ]);
    }

    /**
     * Configure les options du formulaire.
     *
     * Lie ce type de formulaire à l'entité Distributeur.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Distributeur::class,
        ]);
    }
}