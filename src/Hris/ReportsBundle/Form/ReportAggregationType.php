<?php

namespace Hris\ReportsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReportAggregationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('organisationunit','hidden')
            ->add('forms','entity', array(
                'class'=>'HrisFormBundle:Form',
                'multiple'=>true
            ))
            ->add('fields','entity',array(
                'class'=>'HrisFormBundle:Field',
                'query_builder'=>function(EntityRepository $er) {
                    return $er->createQueryBuilder('field')
                        ->innerJoin('field.inputType','inputType')
                        ->where('inputType.name=:inputTypeName')
                        ->setParameter('inputTypeName',"Select")
                        ->orderBy('field.name');
                }
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    public function getName()
    {
        return 'hris_reportsbundle_reportaggregationtype';
    }
}
