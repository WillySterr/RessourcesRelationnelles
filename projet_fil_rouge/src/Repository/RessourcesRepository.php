<?php

namespace App\Repository;

use App\Entity\Ressources;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ressources|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ressources|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ressources[]    findAll()
 * @method Ressources[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RessourcesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ressources::class);
    }

    public function getAllNewsFeed()
    {
        return $this->createQueryBuilder('r')
            ->addOrderBy('r.createdAt', 'DESC')
            ->addOrderBy('r.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getLastRessourceOfCurrentUser($user)
    {
        return $this->createQueryBuilder('q')
            ->addOrderBy('q.createdAt', 'DESC')
            ->addOrderBy('q.updatedAt', 'DESC')
            ->andWhere('q.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->setMaxResults(5)
            ->getResult();

    }

    public function findRessourcesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e
                FROM App:Ressources e
                WHERE e.title LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->setMaxResults(8)
            ->getResult();
    }
    // /**
    //  * @return Ressources[] Returns an array of Ressources objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ressources
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
