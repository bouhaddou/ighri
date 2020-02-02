<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategorieType extends AbstractType
{
    private function getConfig($label,$place )
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
            ->add('designation',TextType::class,$this->getConfig('Désignation  :','Tapez Désignation de catégorie'))
            ->add('content',TextareaType::class, $this->getConfig('Introduction :', 'Tapez super Introduction pour votre catégorie'))
            ->add('image', FileType::class,array('data_class' => null),$this->getConfig("Url de l'image principale :", "Donne l'adresse de l'image qui donne vraiment envie"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
