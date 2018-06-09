<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Usergroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $connection;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    public function isUsernameFree(?string $username): bool
    {
        $sql = "
            SELECT `user`.`id` FROM `users` `user`
            WHERE `user`.`username` = :username;
            ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "username" => $username,
        ]);
        return count($stmt->fetchAll()) === 0;
    }
}
