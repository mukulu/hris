<?php

namespace Hris\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FriendlyReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('name')
            ->add('description')
            ->add('sort')
            ->add('datecreated')
            ->add('lastupdated')
            ->add('serie')
            ->add('arithmeticFilter')
            ->add('relationalFilter')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\FormBundle\Entity\FriendlyReport'
        ));
    }

    public function getName()
    {
        return 'hris_formbundle_friendlyreporttype';
    }
}
