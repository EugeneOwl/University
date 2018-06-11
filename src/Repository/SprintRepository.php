<?php

namespace App\Repository;

use App\Entity\Sprint;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sprint|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sprint|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sprint[]    findAll()
 * @method Sprint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SprintRepository extends ServiceEntityRepository
{
    private $connection;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sprint::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    public function doesSprintExist(string $name): bool
    {
        return $this->findOneBy(["name" => $name]) !== null;
    }

    public function getExecutedTaskCount(Sprint $sprint): int
    {
        $sql = <<<SQL
SELECT COUNT(`task`.`id`) FROM (`sprints` `sprint`, `tasks` `task`)
   JOIN `sprint_task` `s_t`
     ON `s_t`.`sprint_id` = `sprint`.`id`
      AND `s_t`.`task_id` = `task`.`id`
      AND `sprint`.`id` = :sprintId
      AND `task`.`execution` = 1;
SQL;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "sprintId" => $sprint->getId(),
        ]);
        return $stmt->fetch()["COUNT(`task`.`id`)"];
    }

    public function getTaskCount(Sprint $sprint): int
    {
        $sql = <<<SQL
SELECT COUNT(`task`.`id`) FROM (`sprints` `sprint`, `tasks` `task`)
   JOIN `sprint_task` `s_t`
     ON `s_t`.`sprint_id` = `sprint`.`id`
      AND `s_t`.`task_id` = `task`.`id`
      AND `sprint`.`id` = :sprintId;
SQL;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "sprintId" => $sprint->getId(),
        ]);
        return $stmt->fetch()["COUNT(`task`.`id`)"];
    }

    public function getNotDoneTasksFromClosedSprints(string $statusClosed): array
    {
        $sql = <<<SQL
SELECT `task`.`id`
    FROM (`tasks` `task`, `sprints` `sprint`, `sprintstatuses` `status`)
    JOIN (`sprint_task` `s_t`)
      ON `s_t`.`sprint_id` = `sprint`.`id`
        AND `s_t`.`task_id` = `task`.`id`
        AND `sprint`.`status_id` = `status`.`id`
        AND `status`.`name` = :statusClosed
        AND `task`.`execution` = 0;
SQL;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "statusClosed" => $statusClosed,
        ]);
        return $stmt->fetchAll();
    }
}
