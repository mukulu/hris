<?php

namespace Hris\DashboardBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailNotification',null,array(
                'required'=>False,
            ))
            ->add('smsNotification',null,array(
                'required'=>False,
            ))
            ->add('completenessAlert',null,array(
                'required'=>False,
            ))
            ->add('qualityCheckAlert',null,array(
                'required'=>False,
            ))
            ->add('user','hidden',array(
                'required'=>False,
                'mapped'=>False,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\DashboardBundle\Entity\Settings'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hris_dashboardbundle_settings';
    }
}
