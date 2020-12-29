<?php

namespace App\Repository;

use App\Entity\Conversations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conversations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversations[]    findAll()
 * @method Conversations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversations::class);
    }

    // /**
    //  * @return Conversations[] Returns an array of Conversations objects
    //  */

    public function getCurrentUserConversation($currentUserId)
    {
        return $this->createQueryBuilder('c')
            ->andWhere(':currentUserId MEMBER OF c.users')
            ->setParameter('currentUserId', $currentUserId)
            ->addOrderBy('c.lastMessageDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function checkConversationExist($currentUserId, $distantUserId)
    {
        return $this->createQueryBuilder('c')
            ->andWhere(':currentUserId MEMBER OF c.users')
            ->andWhere(':distantUserId MEMBER OF c.users')
            ->setParameter('currentUserId', $currentUserId)
            ->setParameter('distantUserId', $distantUserId)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Conversations
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
