<?php

namespace Hris\OrganisationunitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganisationunitGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('name')
            ->add('dhisUid')
            ->add('code')
            ->add('datecreated')
            ->add('lastupdated')
            ->add('organisationunit')
            ->add('organisationunitGroupset')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\OrganisationunitBundle\Entity\OrganisationunitGroup'
        ));
    }

    public function getName()
    {
        return 'hris_organisationunitbundle_organisationunitgrouptype';
    }
}
