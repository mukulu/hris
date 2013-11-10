<?php

namespace Hris\IntergrationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TIISDataConnectionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('name')
            ->add('recordstablename')
            ->add('organisationunitTableName')
            ->add('organisationunitLongnameColumNname')
            ->add('organisationunitCodeColumnName')
            ->add('organisationunitOwnershipColumnName')
            ->add('recordsOrganisationunitColumnName')
            ->add('recordsInstanceColumnName')
            ->add('tiisParentOrganisationunitCode')
            ->add('hrisTopMostOrganisationunitShrotname')
            ->add('hrisInstituionGroupName')
            ->add('hostUrl')
            ->add('password')
            ->add('username')
            ->add('database')
            ->add('employeeFormName')
            ->add('defaultNationality')
            ->add('defaultHrNationality')
            ->add('datecreated')
            ->add('lastupdated')
            ->add('organisationunit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\IntergrationBundle\Entity\TIISDataConnection'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hris_intergrationbundle_tiisdataconnection';
    }
}
