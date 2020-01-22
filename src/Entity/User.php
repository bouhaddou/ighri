<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class User implements UserInterface
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min=6,  minMessage="Votre Mote de passe doit faire au moins 8 caractères")
     */
    private $PasswordUser;

    /**
     * @Assert\EqualTo(propertyPath="PasswordUser", message="Vous n'avez pas confirmé votre mot de passe")
     */
    public $PasswordConfirmer;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email
     * @Assert\Email(
     *     message = " Email '{{ value }}' n'est pas  valide .")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Posts", mappedBy="author")
     */
    private $posts;
  
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="author", orphanRemoval=true)
     */
    private $comment;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Roles", mappedBy="users")
     */
    private $UserRoles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couverture;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Safran", mappedBy="author")
     */
    private $safrans;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vedio", mappedBy="author")
     */
    private $vedio;

    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if(empty($this->slug)){ $this->slug = "non indique" ; }
        if(empty($this->content)){ $this->content = "non prisez" ; }
        if(empty($this->couverture)){ $this->couverture = "couvert.jpg" ; }
    }
        
    public function getFullname()
    {
        return "{$this->firstname } {$this->lastname}";
    }

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->UserRoles = new ArrayCollection();
        $this->amisone = new ArrayCollection();
        $this->amistwo = new ArrayCollection();
        $this->safrans = new ArrayCollection();
        $this->vedio = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar( $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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

    /**
     * @return Collection|Posts[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Posts $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Posts $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }



    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPasswordUser(): ?string
    {
        return $this->PasswordUser;
    }

    public function setPasswordUser(string $PasswordUser): self
    {
        $this->PasswordUser = $PasswordUser;

        return $this;
    }

    public function getPassword(){
        return $this->PasswordUser;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        $roles = $this->UserRoles->map(function($role){
            return $role->getLibelle();
        })->toArray();

        $roles [] = 'ROLE_USER';
        return $roles;
    }

    public function getSalt()
    { 

    }

    public function eraseCredentials()
    {

     }

    /**
     * @return Collection|Roles[]
     */
    public function getUserRoles(): Collection
    {
        return $this->UserRoles;
    }

    public function addUserRole(Roles $userRole): self
    {
        if (!$this->UserRoles->contains($userRole)) {
            $this->UserRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Roles $userRole): self
    {
        if ($this->UserRoles->contains($userRole)) {
            $this->UserRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    public function getCouverture(): ?string
    {
        return $this->couverture;
    }

    public function setCouverture(?string $couverture): self
    {
        $this->couverture = $couverture;

        return $this;
    }

    

   
    /**
     * @return Collection|Safran[]
     */
    public function getSafrans(): Collection
    {
        return $this->safrans;
    }

    public function addSafran(Safran $safran): self
    {
        if (!$this->safrans->contains($safran)) {
            $this->safrans[] = $safran;
            $safran->setAuthor($this);
        }

        return $this;
    }

    public function removeSafran(Safran $safran): self
    {
        if ($this->safrans->contains($safran)) {
            $this->safrans->removeElement($safran);
            // set the owning side to null (unless already changed)
            if ($safran->getAuthor() === $this) {
                $safran->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vedio[]
     */
    public function getVedio(): Collection
    {
        return $this->vedio;
    }

    public function addVedio(Vedio $vedio): self
    {
        if (!$this->vedio->contains($vedio)) {
            $this->vedio[] = $vedio;
            $vedio->setAuthor($this);
        }

        return $this;
    }

    public function removeVedio(Vedio $datepub): self
    {
        if ($this->vedio->contains($vedio)) {
            $this->vedio->removeElement($vedio);
            // set the owning side to null (unless already changed)
            if ($vedio->getAuthor() === $this) {
                $vedio->setAuthor(null);
            }
        }

        return $this;
    }


     
}
