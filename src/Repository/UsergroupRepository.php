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

    public function getUsergroup(int $userId): ?Usergroup
    {
        $sql = "SELECT * FROM `usergroups` WHERE `user_ids` LIKE :pattern";
        $pattern = "%i:0;i:17%";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["pattern" => $pattern]);
        $usergroupRow = $stmt->fetch()["id"];
        if (empty($usergroupRow)) {
            return null;
        }
        $usergroupId = $stmt->fetch()["id"];
        return $this->find()
    }
}
