<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SafranRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Safran
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(
     *     mimeTypes = {"image/jpeg","image/gif","image/png"},
     *     mimeTypesMessage = "Please upload a valid Image"
     * )
     */
    private $avatar;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datepub;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="safrans")
     */
    private $author;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (empty($this->datepub)) {
            $this->datepub = new \Datetime();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar( $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDatepub(): ?\DateTimeInterface
    {
        return $this->datepub;
    }

    public function setDatepub(\DateTimeInterface $datepub): self
    {
        $this->datepub = $datepub;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
