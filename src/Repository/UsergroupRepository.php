<?php

namespace App\Repository;

use App\Entity\Usergroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Usergroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usergroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usergroup[]    findAll()
 * @method Usergroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsergroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usergroup::class);
    }

    public function doesUsergroupExist(string $name): bool
    {
        return $this->findOneBy(["name" => $name]) !== null;
    }
}
