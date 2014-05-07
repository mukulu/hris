<?php

namespace Hris\IntergrationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Hris\UserBundle\Form\OrganisationunitToIdTransformer;

class DHISDataConnectionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // assuming $entityManager is passed in options
        $em = $options['em'];
        $transformer = new OrganisationunitToIdTransformer($em);
        $builder
            ->add('name')
            ->add($builder->create('parentOrganisationunit','hidden',array(
                    'required'=>True,
                    'constraints'=> array(
                        new NotBlank(),
                    )
                ))->addModelTransformer($transformer)
            )
            ->add('datasetName')
            ->add('datasetUid')
            ->add('hostUrl')
            ->add('username')
            ->add('password')
            ->add('fieldOptionGroupset')
            ->add('datasetHtml')
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
        $resolver->setRequired(
            array('em')
        );
        $resolver->setAllowedTypes(array(
            'em'=>'Doctrine\Common\Persistence\ObjectManager',
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
