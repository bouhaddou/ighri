<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitImageRepository")
 */
class ProduitImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\produits", inversedBy="produitImages")
     */
    private $produit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?produits
    {
        return $this->produit;
    }

    public function setProduit(?produits $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
