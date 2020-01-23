<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditProfileType extends AbstractType
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
            ->add('avatar', FileType::class,[
                'label' => 'url de la photos',
               
                'data_class' => null
            ])
            
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
