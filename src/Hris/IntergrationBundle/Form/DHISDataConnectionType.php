<?php

namespace Hris\IntergrationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DHISDataConnectionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('datasetName')
            ->add('datasetUid')
            ->add('hostUrl')
            ->add('username')
            ->add('password')
            ->add('fieldOptionGroupset')
            ->add('parentOrganisationunit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\IntergrationBundle\Entity\DHISDataConnection'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hris_intergrationbundle_dhisdataconnection';
    }
}
