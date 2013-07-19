<?php

namespace Hris\RecordsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('instance')
            ->add('complete')
            ->add('correct')
            ->add('hashistory')
            ->add('hastraining')
            ->add('datecreated')
            ->add('lastupdated')
            ->add('username')
            ->add('organisationunit')
            ->add('form')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\RecordsBundle\Entity\Record'
        ));
    }

    public function getName()
    {
        return 'hris_recordsbundle_recordtype';
    }
}
