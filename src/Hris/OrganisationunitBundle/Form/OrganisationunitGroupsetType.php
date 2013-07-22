<?php

namespace Hris\OrganisationunitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganisationunitGroupsetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('dhisUid')
            ->add('name')
            ->add('description')
            ->add('compulsory')
            ->add('code')
            ->add('lastupdated')
            ->add('datecreated')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset'
        ));
    }

    public function getName()
    {
        return 'hris_organisationunitbundle_organisationunitgroupsettype';
    }
}
