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
            ->add('description')
            ->add('operator','choice',array( 'choices' => array(
                    'option1' => '==(Equal)',
                    'option2' => '=!(Not Equal)',
                    'option3' => '>(Greater Than)',
                    'option4' => '>=(Greater Than or Equal)',
                    'option5' => '<(Less Than)',
                    'option6' => '<=(Less Than or Equal)',

                  'required'  => false,
                  'empty_data'  => null
            ) ) )
            ->add('leftExpression','entity', array(
                        'class' => 'HrisFormBundle:Field',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('field')
                                ->orderBy('field.name', 'ASC');
                        },
                    )
            )
            ->add('rightExpression','entity', array(
                'class' => 'HrisFormBundle:Field',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('field')
                        ->orderBy('field.name', 'ASC');
                },
            ))
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
