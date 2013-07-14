<?php

namespace Hris\IndicatorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Indicator extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('indicatorName')
            ->add('orgunitGroup')
            ->add('fieldOptionGroup')
            ->add('indicatorYear')
            ->add('expectedValue')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\IndicatorBundle\Entity\Indicator'
        ));
    }

    public function getName()
    {
        return 'hris_indicatorbundle_indicator';
    }
}
