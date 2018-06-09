<?php

namespace App\Repository;

use App\Entity\Usergroup;
use App\Entity\User;
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
    private $connection;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usergroup::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    public function doesUsergroupExist(string $name): bool
    {
        return $this->findOneBy(["name" => $name]) !== null;
    }

    public function findByUser(User $user): ?Usergroup
    {
        $sql = "SELECT usergroup_id FROM usergroup_user WHERE user_id = :userId";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "userId" => $user->getId(),
        ]);
        $usergroupId = $stmt->fetch()["usergroup_id"];
        return $usergroupId ? $this->find($usergroupId) : null;
    }
}
