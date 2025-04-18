<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Produit;
use App\Entity\Reference;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Fixture JoinReferenceFixtures
 *
 * Cette fixture parcourt tous les produits existants en base et, pour chacun,
 * crée une nouvelle référence avec un numéro aléatoire qui est associée au produit.
 * Les références ainsi que les produits modifiés sont ensuite persistés en base.
 *
 * Cette fixture appartient au groupe "group2".
 *
 * @package App\DataFixtures
 */
class JoinReferenceFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * Charge et persiste les associations entre produits et une référence.
     *
     * Pour chaque produit existant, la méthode crée une nouvelle instance de Reference,
     * lui attribue un numéro aléatoire via rand(), puis associe cette référence au produit
     * en appelant la méthode setReference(). Enfin, elle persiste et enregistre les
     * modifications dans la base de données.
     *
     * @param ObjectManager $manager Le gestionnaire d'entités utilisé pour persister les données
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Récupérer tous les produits en base
        $repProduit = $manager->getRepository(Produit::class);
        $listeProduits = $repProduit->findAll();

        foreach ($listeProduits as $monProduit) { // Maintenant, $monProduit est un objet Produit
            // Création d'une nouvelle référence
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

    /**
     * Retourne les groupes de fixtures auxquels appartient cette fixture.
     *
     * @return array Un tableau contenant le nom du groupe, ici ['group2']
     */
    public static function getGroups(): array
    {
        return ['group2'];
    }
}