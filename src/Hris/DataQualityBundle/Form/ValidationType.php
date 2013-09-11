<?php

namespace Hris\DataQualityBundle\Form;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,array $options)
      {
          $builder

            ->add('name','text')
            ->add('description','textarea')
            ->add('leftExpression','textarea')
            ->add('rightExpression','textarea')
             ->add('operator', 'choice', array(
                  'choices'   => array('==(Equal)',
                      '!=(Not Equal)',
                      '>(Greater Than)',
                      '>=(Greater Than or Equal)',
                      '<(Less Than)',
                      '<=(Less Than or Equal)')))
          ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\DataQualityBundle\Entity\Validation'

        ));
    }

    public function getName()
    {
        return 'hris_dataqualitybundle_validationtype';
    }

}
