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
    private $connection;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sprintstatus::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    public function doesTaskTypeExist(string $statusName): bool
    {
        return $this->findOneBy(["name" => $statusName]) !== null;
    }
}
