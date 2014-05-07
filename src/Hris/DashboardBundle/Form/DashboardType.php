<?php

namespace Hris\DashboardBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Hris\DashboardBundle\Form\OrganisationunitToIdTransformer;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

class DashboardType extends AbstractType
{
    #needs to put this as alternative for em variable

     //public function __construct($em) {
     //   $this->em = $em;
     // }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // assuming $entityManager is passed in options
        $em = $options['em'];
        $username = $this->getUsername();
        $transformer = new OrganisationunitToIdTransformer($em);
        $builder
            ->add('name')
            ->add($builder->create('organisationunit','hidden',array(
                    'constraints'=> array(
                        new NotBlank(),
                    ),
                    'mapped'=>False,
                ))->addModelTransformer($transformer)
            )
            ->add('lowerLevels','checkbox',array(
                'required'=>False,
            ))
            ->add('systemWide','checkbox',array(
                'required'=>False,
            ))
            ->add('form','entity', array(
                'class'=>'HrisFormBundle:Form',
                'multiple'=>true,
                'query_builder'=>function(EntityRepository $er) use ($username) {
                    return $er->createQueryBuilder('form')
                        ->join('form.user','user')
                        ->andWhere("user.username='".$username."'")
                        ->orderBy('form.name','ASC');
                },
                'constraints'=>array(
                    new NotBlank(),
                )
            ))
            ->add('fieldOne','entity',array(
                'class'=>'HrisFormBundle:Field',
                'empty_value' => '--SELECT--',
                'query_builder'=>function(EntityRepository $er) {
                    return $er->createQueryBuilder('field')
                        ->innerJoin('field.inputType','inputType')
                        ->where('inputType.name=:inputTypeName')
                        ->orWhere('field.isCalculated=True')
                        ->orWhere('field.skipInReport=True')
                        ->setParameter('inputTypeName',"Select")
                        ->orderBy('field.isCalculated,field.name','ASC');
                },
                'required'=> True,
                'constraints'=> array(
                    new NotBlank(),
                )
            ))
            ->add('fieldTwo',null,array(
                'class'=>'HrisFormBundle:Field',
                'empty_value' => '--SELECT--',
                'query_builder'=>function(EntityRepository $er) {
                    return $er->createQueryBuilder('field')
                        ->innerJoin('field.inputType','inputType')
                        ->where('inputType.name=:inputTypeName')
                        ->orWhere('field.isCalculated=True')
                        ->orWhere('field.skipInReport=True')
                        ->setParameter('inputTypeName',"Select")
                        ->orderBy('field.isCalculated,field.name','ASC');
                },
                'required'=>False,
                'constraints'=> array(
                    new NotBlank(),
                )
            ))
            ->add('graphType','choice',array(
                'choices'=>array(
                    'bar'=>'Bar Chart',
                    'line'=>'Line Chart',
                    'pie'=>'Pie Chart'
                ),
                'constraints'=>array(
                    new NotBlank(),
                )
            ))
            ->add('Submit','submit',array(
                'attr' => array('class' => 'btn'),
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(
            array('em')
        );
        $resolver->setDefaults(array(
            'data_class' => 'Hris\DashboardBundle\Entity\DashboardChart'
        ));
        $resolver->setAllowedTypes(array(
            'em'=>'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hris_dashboardbundle_dashboardtype';
    }
    /**
     * @var string
     */
    private $username;

    /**
     * @param $username
     */
    public function __construct ($username)
    {
        $this->username = $username;
    }



    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
