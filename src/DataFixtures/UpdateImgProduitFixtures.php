<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UpdateImgProduitFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Récupérer tous les produits
        $repProduit = $manager->getRepository(Produit::class);
        $listeProduits = $repProduit->findAll();

        foreach ($listeProduits as $monProduit) {
            switch ($monProduit->getNom()) {
                case 'imprimantes':
                    $monProduit->setLienImage("imprimantes.jpg");
                    break;
                case 'cartouches encre':
                    $monProduit->setLienImage("cartouches.jpg");
                    break;
                case 'ordinateurs':
                    $monProduit->setLienImage("ordinateurs.jpg");
                    break;
                case 'écrans':
                    $monProduit->setLienImage("ecrans.jpg");
                    break;
                case 'claviers':
                    $monProduit->setLienImage("claviers.jpg");
                    break;
                case 'souris':
                    $monProduit->setLienImage("souris.jpg");
                    break;
            }
            $manager->persist($monProduit);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}