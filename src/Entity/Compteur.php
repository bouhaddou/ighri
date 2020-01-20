<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompteurRepository")
 */
class Compteur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cpt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCpt(): ?int
    {
        return $this->cpt;
    }

    public function setCpt(int $cpt): self
    {
        $this->cpt = $cpt;

        return $this;
    }
}
