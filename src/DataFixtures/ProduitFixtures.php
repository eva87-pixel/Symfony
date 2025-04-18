<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Data\ListeProduits;
use App\Entity\Produit;

/**
 * Class ProduitFixtures
 *
 * Cette fixture permet de charger en base une liste de produits
 * définie statiquement dans la classe ListeProduits.
 *
 * Pour chaque entrée de ListeProduits::$mesProduits, un nouveau
 * produit est créé et ses propriétés (nom, prix, quantité, rupture)
 * sont affectées, puis persévérées dans la base de données.
 *
 * @package App\DataFixtures
 */
class ProduitFixtures extends Fixture
{
    /**
     * Charge les données de produits dans la base.
     *
     * Pour chaque produit défini dans ListeProduits::$mesProduits,
     * cette méthode crée un objet Produit, le configure et le persiste.
     * Un flush final est effectué afin d'enregistrer toutes les modifications.
     *
     * @param ObjectManager $manager Le gestionnaire d'entités
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        foreach (ListeProduits::$mesProduits as $monProduit) {
            $produit = new Produit();

            $produit->setNom($monProduit['nom']);
            $produit->setPrix($monProduit['prix']);
            $produit->setQuantite($monProduit['quantite']);
            $produit->setRupture($monProduit['rupture']);
            // autres champs de la table produit

            $manager->persist($produit);
        }
        $manager->flush();
    }
}