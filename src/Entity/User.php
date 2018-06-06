<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserInterface
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
    public $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string", length=30, unique=true)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Usergroup", inversedBy="users")
     * @ORM\JoinColumn(nullable=false) //mapped by и inversed by соответствующие аннатации для пары зависимых сущностей
     */                                //такакя аннатация указывает на сообветствующее поле у противоположной сущности
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

    private $plainPassword;

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    private $plainUsergroupName;

    public function getPlainUsergroupName()
    {
        return $this->plainUsergroupName;
    }

    public function setPlainUsergroupName($plainUsergroupName)
    {
        $this->plainUsergroupName = $plainUsergroupName;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getRoles()
    {
        return ["ROLE_USER"];
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
}
