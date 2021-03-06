<?php

namespace App\Repository;

use App\Entity\Favoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Favoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favoris[]    findAll()
 * @method Favoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favoris::class);
    }

    public function checkFavorisWithUser($user, $ressource){
        return $this->createQueryBuilder('c')
            ->andWhere(':user = c.user')
            ->andWhere(':ressource = c.ressource')
            ->setParameter('user', $user)
            ->setParameter('ressource', $ressource)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getFavorisAboutUser($user){
        return $this->createQueryBuilder('q')
            ->andWhere('q.user = :user')
            ->setParameter('user', $user)
            ->addOrderBy('q.createdAt', 'DESC')
            ->join('q.ressource', 'r')
            ->andWhere('r.published = 1')
            ->getQuery()
            ->getResult();
    }

    public function getLastFavorisOfCurrentUser($user)
    {
        return $this->createQueryBuilder('q')
            ->addOrderBy('q.createdAt', 'DESC')
            ->andWhere('q.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->setMaxResults(5)
            ->getResult();

    }

    // /**
    //  * @return Favoris[] Returns an array of Favoris objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Favoris
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
