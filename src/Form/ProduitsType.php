<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitsType extends AbstractType
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
            ->add('reference',TextType::class,$this->getConfig('Référebce  :','Tapez Référence de produit'))
            ->add('slug',TextType::class,$this->getConfig('Désignation  :','Tapez Désignation de produit'))
            ->add('content',TextareaType::class, $this->getConfig('Introduction :', 'Tapez super Introduction pour votre produit'))
            ->add('poids',NumberType::class, $this->getConfig('Quantité :', 'Tapez la Quantité'))
            ->add('prix',NumberType::class, $this->getConfig('Prix Unitaire :', 'Tapez le Prix de produit'))
            ->add('description',TextareaType::class, $this->getConfig('description :', 'Tapez super description pour votre produit'))
            ->add('informations',TextareaType::class, $this->getConfig('informations complementaire :', 'Tapez super informations pour votre produit'))
            ->add('image', FileType::class,array('data_class' => null),$this->getConfig("Url de l'image principale :", "Donne l'adresse de l'image qui donne vraiment envie"))
            ->add('categories',EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'designation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
