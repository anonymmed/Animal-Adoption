<?php

namespace VenteBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle')->add('description')->add('prix')->add('animal', ChoiceType::class, array(
            'label' => 'animal',
            'choices' => array(
                'Chat'=> 'Chat',
                'Chien' => 'Chien',
                'Poisson' => 'Poisson',
                'Oiseau' => 'Oiseau',
            ),
            'required' => true,
            'multiple' => false,))
            ->add('image', FileType::class, array('label' => 'Image(JPG)'))->add('categorie', EntityType::class, array(
            'class' => 'VenteBundle\Entity\Categorie',
            'choice_label' => 'libelle',
            'multiple' => false
        ))->add('quantite')->add('Ajouter', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VenteBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ventebundle_produit';
    }


}
