<?php

namespace Hris\ImportExportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('session_type')
            ->add('uid')
            ->add('object')
            ->add('status')
            ->add('count')
            ->add('user')
            ->add('starttime')
            ->add('finishtime')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\ImportExportBundle\Entity\History'
        ));
    }

    public function getName()
    {
        return 'hris_importexportbundle_historytype';
    }
}
