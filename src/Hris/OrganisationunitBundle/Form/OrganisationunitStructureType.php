<?php

namespace Hris\OrganisationunitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganisationunitStructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datecreated')
            ->add('lastupdated')
            ->add('organisationunit')
            ->add('level')
            ->add('level1Organisationunit')
            ->add('level2Organisationunit')
            ->add('level3Organisationunit')
            ->add('level4Organisationunit')
            ->add('level5Organisationunit')
            ->add('level6Organisationunit')
            ->add('level7Organisationunit')
            ->add('level8Organisationunit')
            ->add('level9Organisationunit')
            ->add('level10Organisationunit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\OrganisationunitBundle\Entity\OrganisationunitStructure'
        ));
    }

    public function getName()
    {
        return 'hris_organisationunitbundle_organisationunitstructuretype';
    }
}
