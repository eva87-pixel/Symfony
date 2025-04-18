<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures
 *
 * Cette fixture est utilisée pour charger des données initiales dans la base de données.
 * Elle sert principalement de modèle et peut être étendue pour ajouter des données de test.
 *
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * Charge les fixtures dans la base de données.
     *
     * @param ObjectManager $manager Le gestionnaire d'entités pour persister et flush les données
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Exemple de création d'un produit (code en commentaire)
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}