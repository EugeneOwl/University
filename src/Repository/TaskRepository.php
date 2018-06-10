<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    private $connection;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    public function doesTaskExist(string $description): bool
    {
        return $this->findOneBy(["description" => $description]) !== null;
    }

    public function findAllOrderedByPeriod(): array
    {
        $sql = "SELECT * FROM `tasks` ORDER BY `period` ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function executeByIds(array $ids, array $executedIds): int
    {
        $affectedRowsCount = 0;
        $sql = "UPDATE `tasks` SET `execution` = :isDone WHERE `id` = :id AND `execution` != :isDone";
        $stmt = $this->connection->prepare($sql);

        $notExecutedIds = array_diff($ids, $executedIds);
        foreach ($notExecutedIds as $notExecutedId) {
            $stmt->execute([
                "isDone" => 0,
                "id"     => $notExecutedId,
            ]);
            $affectedRowsCount += $stmt->rowCount();
        }
        foreach ($executedIds as $executedId) {
            $stmt->execute([
                "isDone" => 1,
                "id"     => $executedId,
            ]);
            $affectedRowsCount += $stmt->rowCount();
        }
        return $affectedRowsCount;
    }
}
