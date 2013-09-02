<?php

namespace Hris\FormBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResourceTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('resourceTableFieldMember',null, array("multiple"=>"true"))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));
//        $resolver->setDefaults(array(
//            'data_class' => 'Hris\FormBundle\Entity\ResourceTable'
//        ));
    }

    public function getName()
    {
        return 'hris_formbundle_resourcetabletype';
    }
}
