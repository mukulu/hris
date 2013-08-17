<?php

namespace Hris\ReportsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReportAggregationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('organisationunit')
            ->add('forms')
            ->add('fields')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\ReportsBundle\Entity\Report'
        ));
    }

    public function getName()
    {
        return 'hris_reportsbundle_reportaggregationtype';
    }
}
