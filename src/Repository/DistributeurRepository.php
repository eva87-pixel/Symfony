<?php

namespace App\Repository;

use App\Entity\Distributeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class DistributeurRepository
 *
 * Ce repository étend ServiceEntityRepository pour gérer les requêtes spécifiques à l'entité Distributeur.
 *
 * @extends ServiceEntityRepository<Distributeur>
 *
 * @package App\Repository
 */
class DistributeurRepository extends ServiceEntityRepository
{
    /**
     * Constructeur.
     *
     * Initialise le repository avec le ManagerRegistry et lie ce repository à l'entité Distributeur.
     *
     * @param ManagerRegistry $registry Le gestionnaire de registres d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Distributeur::class);
    }

    // Exemple de méthode personnalisée (décommenter et adapter si besoin)
    // /**
    //  * @return Distributeur[] Returns an array of Distributeur objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('d')
    //         ->andWhere('d.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('d.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // Autre exemple de méthode personnalisée (décommenter et adapter si besoin)
    // public function findOneBySomeField($value): ?Distributeur
    // {
    //     return $this->createQueryBuilder('d')
    //         ->andWhere('d.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}