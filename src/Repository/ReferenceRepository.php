<?php

namespace App\Repository;

use App\Entity\Reference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ReferenceRepository
 *
 * Ce repository étend le ServiceEntityRepository pour gérer les requêtes
 * spécifiques à l'entité Reference.
 *
 * @extends ServiceEntityRepository<Reference>
 *
 * @package App\Repository
 */
class ReferenceRepository extends ServiceEntityRepository
{
    /**
     * Constructeur.
     *
     * Initialise le repository avec le ManagerRegistry pour l'entité Reference.
     *
     * @param ManagerRegistry $registry Le gestionnaire de registre d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reference::class);
    }

    // Exemple de méthode personnalisée commentée :
    // /**
    //  * @return Reference[] Retourne un tableau d'objets Reference
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('r')
    //         ->andWhere('r.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('r.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // Exemple de méthode personnalisée commentée :
    // public function findOneBySomeField($value): ?Reference
    // {
    //     return $this->createQueryBuilder('r')
    //         ->andWhere('r.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}