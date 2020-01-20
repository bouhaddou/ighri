<?php

namespace App\Form;

use App\Entity\Posts;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PostType extends AbstractType
{

    private function getConfig($label,$place , $id='id')
    {
        return  [
            'label' => $label,
            'attr' => [
                'id' => $id,
                'placeholder' => $place
            ]
        ];
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('titre',TextType::class,$this->getConfig('Titre :','Tapez super titre pour votre poste', 'titre'))
            ->add('content' ,TextareaType::class, $this->getConfig('Introduction :', 'Tapez super description pour votre poste', 'description'))
            ->add('avatar', FileType::class, array('data_class' => null), $this->getConfig("Url de l'image principale :", "Donne l'adresse de l'image qui donne vraiment envie"))
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
