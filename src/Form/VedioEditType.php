<?php

namespace App\Form;

use App\Entity\Vedio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VedioEditType extends AbstractType
{
    private function getConfig($label,$place,$bol)
    {
        return  [
            'label' => $label,
            'required' => $bol,
            'attr' => [
                'placeholder' => $place
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('titre',TextType::class,$this->getConfig("Titre (*) :","Tapez le Titre de vedio ",true))
        ->add('content',TextareaType::class,$this->getConfig("Introduction :","Tapez super introduction",false))
        ->add('lien',TextareaType::class,$this->getConfig("lien de Vedio (youtube) :","Tapez le lien de vedio ",true))
        ->add('valider',CheckboxType::class,$this->getConfig("Valider la vedio ","Validation de la vedio ",false) )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vedio::class,
        ]);
    }
}
