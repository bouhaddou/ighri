<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class Updatepass
{
  
 
    private $id;

    private $oldpassword;

    /**
     * @Assert\Length( min=6,  minMessage="Votre Mote de passe doit faire au moins 6 caractères !")
     */
    private $newpassword;

    /**
     * @Assert\EqualTo(propertyPath="newpassword", message=" vous n'avez pas comfirmer votre mot de passe")
     */
    private $confirmpassword;


    public function getOldpassword(): ?string
    {
        return $this->oldpassword;
    }

    public function setOldpassword(string $oldpassword): self
    {
        $this->oldpassword = $oldpassword;

        return $this;
    }

    public function getNewpassword(): ?string
    {
        return $this->newpassword;
    }

    public function setNewpassword(string $newpassword): self
    {
        $this->newpassword = $newpassword;

        return $this;
    }

    public function getConfirmpassword(): ?string
    {
        return $this->confirmpassword;
    }

    public function setConfirmpassword(string $confirmpassword): self
    {
        $this->confirmpassword = $confirmpassword;

        return $this;
    }
}
