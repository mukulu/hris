<?php

namespace Hris\OrganisationunitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganisationunitCompletenessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('expectation')
            ->add('lastupdated')
            ->add('datecreated')
            ->add('organisationunit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\OrganisationunitBundle\Entity\OrganisationunitCompleteness'
        ));
    }

    public function getName()
    {
        return 'hris_organisationunitbundle_organisationunitcompletenesstype';
    }
}
