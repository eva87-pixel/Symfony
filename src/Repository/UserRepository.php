<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Class UserRepository
 *
 * Ce repository gère les requêtes spécifiques liées à l'entité User.
 * Il étend le ServiceEntityRepository pour bénéficier des méthodes standards de Doctrine.
 * De plus, il implémente PasswordUpgraderInterface pour permettre la ré-hachage automatique
 * du mot de passe de l'utilisateur au fil du temps.
 *
 * @extends ServiceEntityRepository<User>
 *
 * @package App\Repository
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * Constructeur.
     *
     * Initialise le repository pour l'entité User en utilisant le ManagerRegistry.
     *
     * @param ManagerRegistry $registry Le gestionnaire de registres d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Permet de mettre à jour (rehacher) le mot de passe de l'utilisateur.
     *
     * Cette méthode est appelée pour mettre à jour le mot de passe haché d'un utilisateur
     * lorsque cela est nécessaire (par exemple, lorsque l'algorithme de hachage évolue).
     *
     * @param PasswordAuthenticatedUserInterface $user Le user à mettre à jour.
     * @param string $newHashedPassword Le nouveau mot de passe haché.
     *
     * @throws UnsupportedUserException Si l'utilisateur passé n'est pas une instance de User.
     *
     * @return void
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult();
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult();
    //    }
}