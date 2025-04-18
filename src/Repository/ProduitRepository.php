<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ProduitRepository
 *
 * Ce repository étend le ServiceEntityRepository pour l'entité Produit
 * et fournit des méthodes personnalisées de récupération des produits.
 *
 * @extends ServiceEntityRepository<Produit>
 *
 * @package App\Repository
 */
class ProduitRepository extends ServiceEntityRepository
{
    /**
     * Constructeur.
     *
     * Initialise le repository pour l'entité Produit en utilisant le ManagerRegistry.
     *
     * @param ManagerRegistry $registry Le gestionnaire de registre d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    // Exemple de méthode personnalisée commentée :
    // /**
    //  * @return Produit[] Retourne un tableau d'objets Produit
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('p')
    //         ->andWhere('p.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('p.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // Exemple de méthode personnalisée commentée :
    // public function findOneBySomeField($value): ?Produit
    // {
    //     return $this->createQueryBuilder('p')
    //         ->andWhere('p.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }

    /**
     * Récupère tous les produits triés par ordre décroissant d'ID.
     *
     * Cette méthode exécute une requête DQL pour retourner les produits triés en fonction de leur identifiant,
     * du plus récent au plus ancien.
     *
     * @return array Un tableau d'objets Produit.
     */
    public function orderingProduit()
    {
        $listeProduits = $this->getEntityManager()
            ->createQuery("SELECT p FROM App\Entity\Produit p ORDER BY p.id DESC")
            ->getResult();

        return $listeProduits;
    }

    /**
     * Récupère le dernier produit (celui avec l'ID le plus élevé).
     *
     * Utilise un QueryBuilder pour trier les produits par ordre décroissant et retourne le premier résultat.
     *
     * @return Produit|null Le dernier produit ou null s'il n'y a aucun résultat.
     */
    public function getLastProduit()
    {
        $lastProduit = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $lastProduit;
    }
}