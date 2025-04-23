<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Distributeur;
use App\Form\ReferenceType;
use App\Form\DistributeurType;
use App\Form\Type\MyCheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;

/**
 * Class ProduitType
 *
 * Ce formulaire est utilisé pour la création et la mise à jour d'un produit.
 * Il inclut des champs pour le nom, le prix, la quantité, l'état de rupture (avec un type personnalisé),
 * l'image, la référence et la sélection des distributeurs.
 *
 * @package App\Form
 */
class ProduitType extends AbstractType
{
    /**
     * Configure la construction du formulaire pour l'entité Produit.
     *
     * Ajoute les champs suivants :
     * - "nom" (TextType) avec le label "Nom produit :"
     * - "prix" (NumberType) avec le label "Prix :"
     * - "quantite" (NumberType) avec le label "Quantité :"
     * - "rupture" (MyCheckboxType) avec le label "Rupture de stock ?" (non requis)
     * - "lienImage" (FileType) avec le label "Image :", non requis, sans mapping à une classe de données et avec une valeur vide par défaut
     * - "reference" (ReferenceType) avec le label "Référence du produit" (non requis)
     * - "distributeurs" (EntityType) pour la sélection des distributeurs existants, multiple, non requis
     * - "creer" (SubmitType) pour soumettre le formulaire, avec le label "Insertion d'un produit" et la classe CSS "btn btn-info".
     *
     * Une transformation est ajoutée sur le champ "rupture" pour convertir la valeur entre booléen et entier.
     *
     * @param FormBuilderInterface $builder Le builder pour la construction du formulaire.
     * @param array $options Un tableau d'options.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom produit :'])
            ->add('prix', NumberType::class, ['label' => 'Prix :'])
            ->add('quantite', NumberType::class, ['label' => 'Quantité :'])
            ->add('rupture', MyCheckboxType::class, ['label' => 'Rupture de stock ?', 'required' => false])
            ->add('lienImage', FileType::class, [
                'label'       => 'Image :',
                'required'    => false,
                'data_class'  => null,
                'empty_data'  => 'aucune image'
            ])
            ->add('reference', ReferenceType::class, [
                'label'    => 'Référence du produit',
                'required' => false
            ])
            ->add('distributeurs', EntityType::class, [
                'class'        => Distributeur::class,
                'choice_label' => 'nom',
                'label'        => 'Selection des distributeurs',
                'multiple'     => true,
                'required'     => false
            ])
            ->add('categories', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'name',
                'label'        => 'Catégories',
                'multiple'     => true,
                'expanded'     => true,
                'required'     => false
            ])
            ->add('creer', SubmitType::class, [
                'label' => 'Insertion d\'un produit',
                'attr'  => ['class' => 'btn btn-info']
            ]);

        $builder->get('rupture')->addModelTransformer(new CallbackTransformer(
            fn ($value) => (bool)(int)$value,
            fn ($value) => (int)(bool)$value
        ));
    }

    /**
     * Configure les options du formulaire.
     *
     * Définit le data_class pour lier le formulaire à l'entité Produit.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}