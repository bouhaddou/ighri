<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference')
            ->add('slug')
            ->add('content')
            ->add('poids')
            ->add('prix')
            ->add('description')
            ->add('informations')
            ->add('image')
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
