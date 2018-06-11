<?php

namespace App\Repository;

use App\Entity\User;
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

    public function getUserSprintTasks(User $user): array
    {
        $sql = <<<SQL
SELECT `sprint`.`name`, `task`.`description`, `task`.`execution`
   FROM (
         `tasks` `task`,
         `users` `user`,
         `sprints` `sprint`
         )
   JOIN `sprint_task` `s_t`
     ON `s_t`.`task_id` = `task`.`id` AND
        `s_t`.`sprint_id` = `sprint`.`id`
   JOIN `sprint_user` `s_u`
     ON `s_u`.`user_id` = `user`.`id` AND
        `s_u`.`sprint_id` = `sprint`.`id` AND
        `user`.`id` = :userId;
SQL;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "userId" => $user->getId(),
        ]);
        return $stmt->fetchAll();
    }
}
