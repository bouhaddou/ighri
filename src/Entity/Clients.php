<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientsRepository")
 */
class Clients
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tele;

    /**
     * @ORM\Column(type="text")
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ventes", mappedBy="client")
     */
    private $ventes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $maison;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pays;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    public function __construct()
    {
        $this->ventes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTele(): ?string
    {
        return $this->tele;
    }

    public function setTele(string $tele): self
    {
        $this->tele = $tele;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection|Ventes[]
     */
    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Ventes $vente): self
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes[] = $vente;
            $vente->setClient($this);
        }

        return $this;
    }

    public function removeVente(Ventes $vente): self
    {
        if ($this->ventes->contains($vente)) {
            $this->ventes->removeElement($vente);
            // set the owning side to null (unless already changed)
            if ($vente->getClient() === $this) {
                $vente->setClient(null);
            }
        }

        return $this;
    }

    public function getMaison(): ?string
    {
        return $this->maison;
    }

    public function setMaison(?string $maison): self
    {
        $this->maison = $maison;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getPostal(): ?int
    {
        return $this->postal;
    }

    public function setPostal(?int $postal): self
    {
        $this->postal = $postal;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
