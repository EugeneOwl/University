<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User
{
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
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
     * @ORM\Column(type="string", length=30)
     */
    private $username;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @ORM\Column(type="string", length=4096)
     */
    private $password;

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usergroup")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usergroup;

    public function getUsergroup()
    {
        return $this->usergroup;
    }

    public function setUsergroup(Usergroup $usergroup): self
    {
        $this->usergroup = $usergroup;

        return $this;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Task", inversedBy="users")
     * @ORM\JoinTable(name="users_tasks")
     */
    private $tasks;

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $tasks): self
    {
        $this->tasks[] = $tasks;

        return $this;
    }
}
