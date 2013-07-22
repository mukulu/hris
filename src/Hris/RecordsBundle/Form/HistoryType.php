<?php

namespace Hris\RecordsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('instance')
            ->add('history')
            ->add('startdate')
            ->add('reason')
            ->add('username')
            ->add('datecreated')
            ->add('lastupdated')
            ->add('record')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\RecordsBundle\Entity\History'
        ));
    }

    public function getName()
    {
        return 'hris_recordsbundle_historytype';
    }
}
