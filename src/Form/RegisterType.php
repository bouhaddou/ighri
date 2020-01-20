<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    private function getConfig($label, $place,$rool)
    {
        return  [
            'label' => $label, 
            'required'    => $rool,
            'attr' => [
                'placeholder' => $place
               
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',TextType::class,$this->getConfig("Prénom (*) :","Tapez Votre Prénom",true))
            ->add('lastname', TextType::class, $this->getConfig("Nom (*) :", "Tapez Votre Nom",true))
            ->add('email', TextType::class, $this->getConfig("Adresse Email (*) :", "Tapez Votre Adresse Email ",true))
            ->add('avatar', FileType::class, $this->getConfig("Url de votre Photos (*) :", "Donne l'adresse de votre photos",false))
            ->add('PasswordUser', PasswordType::class, $this->getConfig("Mot de Passe (*) :", "Tapez Votre Mot de passe",true))
            ->add('PasswordConfirmer', PasswordType::class, $this->getConfig("Confirmation de Mot de Passe (*) :", "Veuillez Confirmer  Votre Mot de passe",true))
            ->add('slug', TextType::class, $this->getConfig("Introduction  : (optionnel)", "Entrer votre specialité (job)",false))
            ->add('content',TextareaType::class, $this->getConfig("Présentation Professionnelle : (optionnel)", "Merci de présenter votre Carrière professionnelle",false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
