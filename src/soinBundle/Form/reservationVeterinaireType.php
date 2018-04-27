<?php

namespace soinBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class reservationVeterinaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date_debut')
                 ->add('description', ChoiceType::class, array(
                     'choices' => [
                        'consultation',
                         'operation'
                     ],
                     'choice_label' => function($var, $key, $index) {
                         return strtoupper($var);
                     },
                /*'choice_label' => 'consultation','operation',
                'multiple' => false ,*/
            ))

        ->add('save', SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'soinBundle\Entity\reservationVeterinaire'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'soinbundle_reservationveterinaire';
    }


}
