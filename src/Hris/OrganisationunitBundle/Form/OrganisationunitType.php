<?php

namespace Hris\OrganisationunitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganisationunitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('dhisUid')
            ->add('code')
            ->add('shortname')
            ->add('longname')
            ->add('active')
            ->add('openingdate')
            ->add('closingdate')
            ->add('geocode')
            ->add('coordinates')
            ->add('featuretype')
            ->add('address')
            ->add('email')
            ->add('phonenumber')
            ->add('contactperson')
            ->add('description')
            ->add('datecreated')
            ->add('lastupdated')
            ->add('userInfo')
            ->add('organisationunitGroup')
            ->add('dashboardChart')
            ->add('parent')
            ->add('organisationunitStructure')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\OrganisationunitBundle\Entity\Organisationunit'
        ));
    }

    public function getName()
    {
        return 'hris_organisationunitbundle_organisationunittype';
    }
}
