<?php

namespace Hris\DataQualityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('name')
            ->add('description')
            ->add('operator')
            ->add('leftExpression')
            ->add('rightExpression')
            ->add('datecreated')
            ->add('lastupdated')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\DataQualityBundle\Entity\Validation'
        ));
    }

    public function getName()
    {
        return 'hris_dataqualitybundle_validationtype';
    }
}
