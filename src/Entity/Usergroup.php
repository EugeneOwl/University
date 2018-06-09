<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsergroupRepository")
 * @ORM\Table(name="usergroups")
 */
class Usergroup
{
    public function __construct()
    {
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
     * @ORM\Column(type="array")
     */
    private $user_ids;

    public function getUsers(): array
    {
        return $this->user_ids;
    }

    public function addUser(User $user): self
    {
        if (empty($this->user_ids) || !in_array($user->getId(), $this->user_ids)) {
            $this->user_ids[] = $user->getId();
        }

        return $this;
    }
}
