<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SprintRepository")
 * @ORM\Table(name="sprints")
 */
class Sprint
{
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->usergroups = new ArrayCollection();
    }

    public $plainSprint;

    public function getPlainSprint(): ?int
    {
        return $this->plainSprint;
    }

    public function setPlainSprint(int $plainSprint): self
    {
        $this->plainSprint = $plainSprint;

        return $this;
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
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sprintstatus")
     * @ORM\JoinColumn(name="status_id")
     */
    private $status;

    public function getStatus(): ?Sprintstatus
    {
        return $this->status;
    }

    public function setStatus(Sprintstatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    private $plainStatus;

    public function getPlainStatus(): ?string
    {
        return $this->plainStatus;
    }

    public function setPlainStatus(int $plainStatus): self
    {
        $this->plainStatus = $plainStatus;

        return $this;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Task")
     * @ORM\JoinTable(name="sprint_task")
     */
    private $tasks;

    public function getTasks(): ?Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        $this->tasks[] = $task;

        return $this;
    }

    private $plainTask;

    public function getPlainTask(): ?int
    {
        return $this->plainTask;
    }

    public function setPlainTask(int $plainTask): self
    {
        $this->plainTask = $plainTask;

        return $this;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     * @ORM\JoinTable(name="sprint_user")
     */
    private $users;

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        $this->users[] = $user;

        return $this;
    }

    private $plainUser;

    public function getPlainUser(): ?int
    {
        return $this->plainUser;
    }

    public function setPlainUser(int $plainUser): self
    {
        $this->plainUser = $plainUser;

        return $this;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Usergroup")
     * @ORM\JoinTable(name="sprint_usergroup")
     */
    private $usergroups;

    public function getUsergroups(): Collection
    {
        return $this->usergroups;
    }

    public function addUsergroup(Usergroup $usergroup): self
    {
        $this->usergroups[] = $usergroup;

        return $this;
    }

    private $plainUsergroup;

    public function getPlainUsergroup(): ?int
    {
        return $this->plainUsergroup;
    }

    public function setPlainUsergroup(int $plainUsergroup): self
    {
        $this->plainUsergroup = $plainUsergroup;

        return $this;
    }
}
