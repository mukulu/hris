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
            ->add('name')
            ->add('caption')
            ->add('description')
            ->add('compulsory')
            ->add('isUnique')
            ->add('hashistory')
            ->add('dataType')
            ->add('inputType')
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
