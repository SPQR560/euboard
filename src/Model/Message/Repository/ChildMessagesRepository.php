<?php

namespace App\Model\Message\Repository;

use App\Model\Message\Entity\ChildMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChildMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChildMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChildMessages[]    findAll()
 * @method ChildMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChildMessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChildMessages::class);
    }

    // /**
    //  * @return ChildMessages[] Returns an array of ChildMessages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChildMessages
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
