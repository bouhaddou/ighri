<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AmisRepository")
 */
class Amis
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="amisone")
     * @ORM\JoinColumn(nullable=false)
     */
    private $friend_envoi;

  

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $relation;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFriendEnvoi(): ?User
    {
        return $this->friend_envoi;
    }

    public function setFriendEnvoi(?User $friend_envoi): self
    {
        $this->friend_envoi = $friend_envoi;

        return $this;
    }

  
    public function getRelation(): ?string
    {
        return $this->relation;
    }

    public function setRelation(string $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}
