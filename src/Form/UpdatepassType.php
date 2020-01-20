<?php

namespace App\Form;

use App\Entity\Updatepass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdatepassType extends AbstractType
{
    private function getConfig($label, $place)
    {
        return  [
            'label' => $label,
            'attr' => [
                'placeholder' => $place
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('oldpassword', PasswordType::class, $this->getConfig("Mot de Passe :", "Tapez Votre ancien Mot de passe"))
        ->add('newpassword', PasswordType::class, $this->getConfig("Mot de Passe :", "Tapez Votre nouveau Mot de passe"))
        ->add('confirmpassword', PasswordType::class, $this->getConfig("Confirmation Mot de Passe :", "Veuillez Confirmer  Votre Mot de passe"))
       ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Updatepass::class,
        ]);
    }
}
