<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Produit;
use App\Entity\Distributeur;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Fixture pour créer des jointures entre Produit et Distributeur.
 *
 * Cette fixture associe certains produits existants à des distributeurs prédéfinis.
 * Les distributeurs sont créés et ensuite liés aux produits selon leur nom.
 * La persistance s'effectue grâce au cascade "persist" défini dans l'entité Produit.
 *
 * @package App\DataFixtures
 */
class JoinDistributeurFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * Charge et insère les associations entre produits et distributeurs.
     *
     * @param ObjectManager $manager Le gestionnaire d'entités
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Récupérer le repository des produits
        $repProduit = $manager->getRepository(Produit::class);

        // Création des objets Distributeur
        $logitech = new Distributeur;
        $logitech->setNom('Logitech');
        $manager->persist($logitech);

        $hp = new Distributeur;
        $hp->setNom('HP');
        $manager->persist($hp);

        $epson = new Distributeur;
        $epson->setNom('Epson');
        $manager->persist($epson);

        $dell = new Distributeur;
        $dell->setNom('Dell');
        $manager->persist($dell);

        $acer = new Distributeur;
        $acer->setNom('Acer');
        $manager->persist($acer);

        // Création des jointures entre produits et distributeurs
        $produit = $repProduit->findOneBy(array('nom' => 'souris'));
        if ($produit !== null) {
        $produit->addDistributeur($hp);
        $produit->addDistributeur($logitech);
        }

        $produit = $repProduit->findOneBy(array('nom' => 'écrans'));
        if ($produit !== null) {
        $produit->addDistributeur($hp);
        $produit->addDistributeur($dell);
        }

        $produit = $repProduit->findOneBy(array('nom' => 'claviers'));
        if ($produit !== null) {
        $produit->addDistributeur($hp);
        $produit->addDistributeur($logitech);
        }

        $produit = $repProduit->findOneBy(array('nom' => 'ordinateurs'));
        if ($produit !== null) {
        $produit->addDistributeur($hp);
        $produit->addDistributeur($dell);
        $produit->addDistributeur($acer);
        }

        $produit = $repProduit->findOneBy(array('nom' => 'cartouches encre'));
        if ($produit !== null) {
        $produit->addDistributeur($epson);
        }

        $produit = $repProduit->findOneBy(array('nom' => 'imprimantes'));
        if ($produit !== null) {
        $produit->addDistributeur($epson);
        $produit->addDistributeur($hp);
        }

        // Persist uniquement le dernier produit récupéré
        $manager->persist($produit);

        // Pas besoin d'appeler persist() sur les distributeurs grâce à l'option cascade "persist"
        $manager->flush();
    }

    /**
     * Retourne les groupes de fixtures auxquels appartient cette fixture.
     *
     * @return array
     */
    public static function getGroups(): array
    {
        return ['group3'];
    }
}