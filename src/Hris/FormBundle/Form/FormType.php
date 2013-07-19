<?php

namespace Hris\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('name')
            ->add('hypertext')
            ->add('title')
            ->add('datecreated')
            ->add('lastupdated')
            ->add('uniqueRecordFields')
            ->add('dashboardChart')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\FormBundle\Entity\Form'
        ));
    }

    public function getName()
    {
        return 'hris_formbundle_formtype';
    }
}
