<?php

namespace Hris\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('name')
            ->add('caption')
            ->add('compulsory')
            ->add('unique')
            ->add('description')
            ->add('hashistory')
            ->add('fieldrelation')
            ->add('datecreated')
            ->add('lastupdated')
            ->add('fieldGroup')
            ->add('parentField')
            ->add('childField')
            ->add('dataType')
            ->add('inputType')
            ->add('uniqueRecordForms')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\FormBundle\Entity\Field'
        ));
    }

    public function getName()
    {
        return 'hris_formbundle_fieldtype';
    }
}
