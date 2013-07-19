<?php

namespace Hris\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldOptionGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('name')
            ->add('description')
            ->add('datecreated')
            ->add('lastmodified')
            ->add('fieldOption')
            ->add('field')
            ->add('fieldOptionGroupset')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\FormBundle\Entity\FieldOptionGroup'
        ));
    }

    public function getName()
    {
        return 'hris_formbundle_fieldoptiongrouptype';
    }
}
