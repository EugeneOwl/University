<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SprintRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Sprintstatus")
     * @ORM\JoinColumn(name="status_id")
     */
    private $status;

    public function getStatus(): Sprintstatus
    {
        return $this->status;
    }

    public function setStatus(Sprintstatus $status): self
    {
        $this->status = $status;

        return $this;
    }
}
