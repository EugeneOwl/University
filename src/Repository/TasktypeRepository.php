<?php

namespace App\Repository;

use App\Entity\Tasktype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tasktype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tasktype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tasktype[]    findAll()
 * @method Tasktype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasktypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tasktype::class);
    }

//    /**
//     * @return Tasktype[] Returns an array of Tasktype objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tasktype
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
