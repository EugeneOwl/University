<?php

namespace App\Repository;

use App\Entity\Sprintstatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sprintstatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sprintstatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sprintstatus[]    findAll()
 * @method Sprintstatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SprintstatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sprintstatus::class);
    }

//    /**
//     * @return Sprintstatus[] Returns an array of Sprintstatus objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sprintstatus
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
