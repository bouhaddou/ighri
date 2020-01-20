<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    private function getConfig($label,$place)
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
            ->add('Nomcomplet',TextType::class,$this->getConfig("Nom Complet :","Tapez Votre nom complet"))
            ->add('email', TextType::class, $this->getConfig("Adresse Email :", "Tapez Votre Adresse Email "))
            ->add('objet',TextType::class,$this->getConfig('objet :','Tapez super titre pour votre poste'))
            ->add('message',TextareaType::class, $this->getConfig("Message :", "Merci de Tapé votre Message "))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
