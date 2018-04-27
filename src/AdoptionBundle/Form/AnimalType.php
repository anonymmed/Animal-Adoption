<?php

namespace AdoptionBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')->add('espece')->add('race')->add('sexe')->add('age')->add('taille')->add('region')->add('idRefuge', EntityType::class, array(
            'class' => 'AdoptionBundle\Entity\Refuge',
            'choice_label' => 'libelle',
            'multiple' => false
        ))->add('description')
            ->add('image', FileType::class, array('label' => 'Image(JPG)'))
            ->add('Ajouter', SubmitType::class);;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdoptionBundle\Entity\Animal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adoptionbundle_animal';
    }


}
