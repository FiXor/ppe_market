<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,  array('label' => false))
            ->add('prenom',null,  array('label' => false))
            ->add('username',null,  array('label' => false))
            ->add('courriel',null,  array('label' => false))
            ->add('telephone',null,  array('label' => false))
            ->add('dateNaissance',null,  array('label' => false))
            ->add('password', PasswordType::class, array('label' => false))
            ->add('confirmeMotDePasse', PasswordType::class, array('label' => false))
            //->add('idImage')
            // ->add('idRole')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
