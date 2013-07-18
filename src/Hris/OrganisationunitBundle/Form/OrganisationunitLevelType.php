<?php

namespace Hris\OrganisationunitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganisationunitLevelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('dhisUid')
            ->add('level')
            ->add('name')
            ->add('description')
            ->add('dataentrylevel')
            ->add('datecreated')
            ->add('lastupdated')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\OrganisationunitBundle\Entity\OrganisationunitLevel'
        ));
    }

    public function getName()
    {
        return 'hris_organisationunitbundle_organisationunitleveltype';
    }
}
