<?php

namespace App\Form;

use App\Entity\PackNormal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackNormalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                "label" => "Nom et prénom"
            ])
            ->add('email', null, [
                "label" => "Email"
            ])
            ->add('phone', null, [
                "label" => "Numéro de téléphone pour WhatsApp"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PackNormal::class,
        ]);
    }
}
