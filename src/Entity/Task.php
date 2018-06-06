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
     * @ORM\ManyToOne(targetEntity="App\Entity\Tasktype", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
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

    private $plainTasktype;

    public function getPlainTasktype()
    {
        return $this->plainTasktype;
    }

    public function setPlainTasktype($plainTasktype): void
    {
        $this->plainTasktype = $plainTasktype;
    }
}
