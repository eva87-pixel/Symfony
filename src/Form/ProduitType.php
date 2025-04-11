<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\ReferenceType;
use App\Form\DistributeurType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\CallbackTransformer;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Distributeur;
use App\Form\Type\MyCheckboxType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('nom', TextType::class, ['label' => 'Nom produit :'])
           ->add('prix', NumberType::class, ['label' => 'Prix :'])
           ->add('quantite', NumberType::class, ['label' => 'Quantité :'])
           ->add('rupture', MyCheckboxType::class, ['label'=> 'Rupture de stock ?', 'required' => false])
           ->add('lienImage', FileType::class, [
               'label' => 'Image :',
               'required' => false,
               'data_class' => null,
               'empty_data' => 'aucune image'
           ])
           ->add('reference', ReferenceType::class, [
               'label' =>'Référence du produit',
               "required"  => false
           ])
           /*->add('distributeurs', CollectionType::class, [
               'entry_type' => DistributeurType::class,
               'allow_add' => true,
               'allow_delete' => true,
               'by_reference' => false,
               'label' => false
           ])*/
           ->add('distributeurs',EntityType::class,array(
               'class' => Distributeur::class,
               'choice_label'=>'nom',
               'label' =>'Selection des distributeurs',
               'multiple' => true,
               'required' => false
           ))
           ->add('creer', SubmitType::class, [
               'label' => 'Insertion d\'un produit',
               'attr' => ['class' => 'btn btn-info']
           ]);

        $builder->get('rupture')->addModelTransformer(new CallbackTransformer(
            fn ($value) => (bool)(int)$value,
            fn ($value) => (int)(bool)$value
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}