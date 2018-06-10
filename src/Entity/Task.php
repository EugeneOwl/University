<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 * @ORM\Table(name="tasks")
 */
class Task
{
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @ORM\Column(type="integer")
     */
    private $period;

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod($period): self
    {
        if (ctype_digit($period)) {
            $this->period = $period;
        }

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tasktype")
     * @ORM\JoinColumn(nullable=true)
     */
    private $tasktype;

    public function getTasktype(): ?Tasktype
    {
        return $this->tasktype;
    }

    public function setTasktype(Tasktype $tasktype): self
    {
        $this->tasktype = $tasktype;

        return $this;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="tasks")
     */
    private $users;

    /**
     * @return Collection|User
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * @ORM\Column(type="boolean", name="execution")
     */
    private $isDone;

    public function getIsDone(): bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }

    private $plainTasktypeId;

    public function getPlainTasktypeId()
    {
        return $this->plainTasktypeId;
    }

    public function setPlainTasktypeId($plainTasktypeId): void
    {
        $this->plainTasktypeId = $plainTasktypeId;
    }

    private $plainUsers;

    public function getPlainUsers()
    {
        return $this->plainUsers;
    }

    public function setPlainUsers($plainUsers): void
    {
        $this->plainUsers = $plainUsers;
    }

    private $plainTasks;

    public function getPlainTasks()
    {
        return $this->plainTasks;
    }

    public function setPlainTasks($plainTasks): void
    {
        $this->plainTasks = $plainTasks;
    }
}
