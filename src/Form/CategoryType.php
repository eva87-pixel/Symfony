<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// N'oublie pas d'importer le type de champ
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // On ajoute ici le champ "name" (propriété de l'entité)
            ->add('name', TextType::class, [
                'label' => 'Nom de la catégorie',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // On rattache le formulaire à l'entité Category
            'data_class' => Category::class,
        ]);
    }
}