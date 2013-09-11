<?php

namespace Hris\DataQualityBundle\Form;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValidationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)

      {
          $builder

            ->add('name','text')
            ->add('description','textarea')
            ->add('leftExpression','textarea',array(
                  'attr' => array('cols' => '29', 'rows' => '8')))
            ->add('rightExpression','textarea',array(
                  'attr' => array('cols' => '29', 'rows' => '8')))
             ->add('operator', 'choice',array(
                  'choices'   => array(
                      '--Select--'=>'--Select--',
                      '==(Equal)'=>'==(Equal)',
                      '!=(Not Equal)'=>'!=(Not Equal)',
                      '>(Greater Than)'=>'>(Greater Than)',
                      '>=(Greater Than or Equal)'=>'>=(Greater Than or Equal)',
                      '<(Less Than)'=>'<(Less Than)',
                      '<=(Less Than or Equal)'=>'<=(Less Than or Equal)')))
          ->add('submit','submit')
          ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\DataQualityBundle\Entity\Validation',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'=> 'create_validation',
        ));
    }

    public function getName()
    {
        return 'hris_dataqualitybundle_validationtype';
    }

}
