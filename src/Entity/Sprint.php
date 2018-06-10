<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SprintRepository")
 * @ORM\Table(name="sprints")
 */
class Sprint
{
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
}
