<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('firstname', TextType::class, [
                'label' => "Prénom",
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom de famille",
            ])
            ->add('birthday', TextType::class, [
                'label' => "Date de naissance",
            ])
            ->add('birthcity', TextType::class, [
                'label' => "Ville de naissance",
            ])
            ->add('street', TextType::class, [
                'label' => "Adresse",
            ])
            ->add('postalCode', TextType::class, [
                'label' => "Code postal",
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
            ])
            ->add('save', SubmitType::class, [
                'label' => "Générer votre URL personnalisée",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserData::class,
        ]);
    }
}
