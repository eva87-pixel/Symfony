<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Data\ListeProduits;
use App\Entity\Produit;
use App\Entity\Reference;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class JoinReferenceFixtures extends Fixture implements FixtureGroupInterface
{
   public function load(ObjectManager $manager): void
   {
       // Récupérer tous les produits en base
       $repProduit = $manager->getRepository(Produit::class);
       $listeProduits = $repProduit->findAll();

       foreach ($listeProduits as $monProduit) { // Maintenant, $monProduit est un objet Produit

           $reference = new Reference();
           $reference->setNumero(rand()); // Génère un numéro aléatoire

           // Associer la référence au produit
           $monProduit->setReference($reference);

           // Persister la référence et le produit
           $manager->persist($reference);
           $manager->persist($monProduit);
       }

       // Enregistrer en base
       $manager->flush();
   }

public static function getGroups(): array
{
return ['group2'];
}
}
