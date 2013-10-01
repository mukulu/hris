<?php

namespace Hris\FormBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class FieldOptionMergeVisibleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field','entity',array(
                'class'=>'HrisFormBundle:Field',
                'query_builder'=>function(EntityRepository $er) {
                    return $er->createQueryBuilder('field')
                        ->innerJoin('field.inputType','inputType')
                        ->where('inputType.name=:inputTypeName')
                        ->setParameter('inputTypeName',"Select")
                        ->orderBy('field.isCalculated,field.name','ASC');
                },
                'constraints'=> array(
                    new NotBlank(),
                )
            ))
            ->add('mergedFieldOption')
            ->add('removedFieldOptionValue')
            ->add('removedFieldOptionUid')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\FormBundle\Entity\FieldOptionMerge'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hris_formbundle_fieldoptionmerge';
    }
}
