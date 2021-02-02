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
            ->andWhere('r.published = 1')
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

    public function findRessourcesByString($str)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e
                FROM App:Ressources e
                WHERE e.title LIKE :str AND e.published = 1
                '
            )
            ->setParameter('str', '%' . $str . '%')
            ->setMaxResults(8)
            ->getResult();
    }

    //TRI DES RESSOURCES

    public function getRessourcesByDesc()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.updatedAt', 'DESC')
            ->addOrderBy('r.createdAt', 'DESC')
            ->andWhere('r.published = 1')
            ->getQuery()
            ->getResult();
    }
    public function getRessourcesByAsc()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.updatedAt', 'ASC')
            ->addOrderBy('r.createdAt', 'ASC')
            ->andWhere('r.published = 1')
            ->getQuery()
            ->getResult();
    }
    public function getRessourcesByCat($cat)
    {
        $em = $this->getEntityManager();

        $queryText  = "SELECT s FROM App:Ressources s ";
        $queryText .= "WHERE :category MEMBER OF s.category AND s.published = 1";

        $query = $em->createQuery($queryText);
        $query->setParameter('category', $cat);

        return $query->getResult();
    }



    public function getRessourcesByTriAndFilterAsc($cat)
    {
        return $this->createQueryBuilder('c')
            ->andWhere(':category MEMBER OF c.category')
            ->andWhere('c.published = 1')
            ->setParameter('category', $cat)
            ->orderBy('c.updatedAt', 'ASC')
            ->addOrderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }



    public function getRessourcesByTriAndFilterDesc($cat)
    {
        return $this->createQueryBuilder('c')
            ->andWhere(':category MEMBER OF c.category')
            ->andWhere('c.published = 1')
            ->setParameter('category', $cat)
            ->orderBy('c.updatedAt', 'DESC')
            ->addOrderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    // public function getOldRessources()
    // {
    //     return $this->createQueryBuilder('r')
    //         ->addOrderBy('r.createdAt', 'ASC')
    //         ->andWhere('r.cat = :cat')
    //         ->getQuery()
    //         ->getResult();
    // }



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
