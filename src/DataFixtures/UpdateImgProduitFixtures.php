<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Fixture pour mettre à jour les images des produits.
 *
 * Pour chaque produit existant dans la base de données, cette fixture met à jour la propriété
 * "lienImage" en fonction du nom du produit.
 *
 * - Si le nom est "imprimantes", le lien d'image sera "imprimantes.jpg".
 * - Si le nom est "cartouches encre", le lien sera "cartouches.jpg".
 * - Si le nom est "ordinateurs", le lien sera "ordinateurs.jpg".
 * - Si le nom est "écrans", le lien sera "ecrans.jpg".
 * - Si le nom est "claviers", le lien sera "claviers.jpg".
 * - Si le nom est "souris", le lien sera "souris.jpg".
 *
 * Cette fixture fait partie du groupe "group1".
 *
 * @package App\DataFixtures
 */
class UpdateImgProduitFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * Charge et met à jour les images associées aux produits.
     *
     * Parcourt l'ensemble des produits existants et, selon le nom du produit,
     * assigne un fichier image approprié à la propriété "lienImage".
     *
     * @param ObjectManager $manager Le gestionnaire d'entités utilisé pour persister les modifications.
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Récupérer tous les produits dans la base de données
        $repProduit = $manager->getRepository(Produit::class);
        $listeProduits = $repProduit->findAll();

        // Pour chaque produit, mettre à jour le lien d'image en fonction du nom
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

    /**
     * Retourne les groupes de fixtures auxquels cette fixture appartient.
     *
     * @return array Un tableau contenant le nom du groupe, ici "group1".
     */
    public static function getGroups(): array
    {
        return ['group1'];
    }
}