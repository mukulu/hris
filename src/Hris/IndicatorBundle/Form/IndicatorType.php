<?php

namespace Hris\IndicatorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IndicatorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('value')
            ->add('year')
            ->add('fieldOptionGroup')
            ->add('organisationunitGroup');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\IndicatorBundle\Entity\Indicator'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hris_indicatorbundle_indicatortype';
    }
}
