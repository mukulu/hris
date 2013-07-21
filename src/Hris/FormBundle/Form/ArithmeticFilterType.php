<?php

namespace Hris\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArithmeticFilterType extends AbstractType
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
            ->add('friendlyReport')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\FormBundle\Entity\ArithmeticFilter'
        ));
    }

    public function getName()
    {
        return 'hris_formbundle_arithmeticfiltertype';
    }
}
