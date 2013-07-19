<?php

namespace Hris\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResourceTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('name')
            ->add('datecreated')
            ->add('lastupdated')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\FormBundle\Entity\ResourceTable'
        ));
    }

    public function getName()
    {
        return 'hris_formbundle_resourcetabletype';
    }
}
