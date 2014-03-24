<?php
/*
 *
 * Copyright 2012 Human Resource Information System
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */
namespace Hris\FormBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class FieldOptionType extends AbstractType
{

    /**
     * @return integer
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * @return string
     */
    public function getFieldOptionValue()
    {
        return $this->fieldOptionValue;
    }

    /**
     * @var integer
     */
    private $fieldId;

    /**
     * @var string
     */
    private $fieldOptionValue;

    /**
     * @param $fieldId
     * @param $fieldOptionValue
     */
    public function __construct ($fieldId,$fieldOptionValue=null)
    {
        $this->fieldId = $fieldId;
        $this->fieldOptionValue = $fieldOptionValue;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fieldId = $this->getFieldId();
        $fieldOptionValue = $this->getFieldOptionValue();
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
            ->add('sort',null,array(
                'required'=>False,
            ))
            ->add('value')
            ->add('description',null,array(
                'required'=>false,
            ))
            ->add('skipInReport',null,array(
                'required'=>False,
            ))
            ->add('childFieldOption','entity', array(
                'class'=>'HrisFormBundle:FieldOption',
                'multiple'=>true,
                'query_builder'=>function(EntityRepository $er) use ($fieldId) {
                        return $er->createQueryBuilder('fieldOption')
                            ->join('fieldOption.field','field')
                            ->andWhere("field.id='".$fieldId."'")
                            ->orderBy('fieldOption.value','ASC');
                    },
                'constraints'=>array(
                    new NotBlank(),
                ),
                'required'=>False,
            ))
        ;

        if(!empty($this->fieldOptionValue)) {
            $builder
                ->add('fieldOptionMerge','entity', array(
                    'class'=>'HrisFormBundle:FieldOption',
                    'multiple'=>true,
                    'query_builder'=>function(EntityRepository $er) use ($fieldId,$fieldOptionValue) {
                            return $er->createQueryBuilder('fieldOption')
                                ->join('fieldOption.field','field')
                                ->andWhere("field.id='".$fieldId."'")
                                ->andWhere("fieldOption.value!='".$fieldOptionValue."'")
                                ->orderBy('fieldOption.value','ASC');
                        },
                    'constraints'=>array(
                        new NotBlank(),
                    ),
                    'mapped'=>False,
                    'required'=>False,
                ))
                ;
        }else {
            $builder
                ->add('fieldOptionMerge','entity', array(
                    'class'=>'HrisFormBundle:FieldOption',
                    'multiple'=>true,
                    'query_builder'=>function(EntityRepository $er) use ($fieldId) {
                            return $er->createQueryBuilder('fieldOption')
                                ->join('fieldOption.field','field')
                                ->andWhere("field.id='".$fieldId."'")
                                ->orderBy('fieldOption.value','ASC');
                        },
                    'constraints'=>array(
                        new NotBlank(),
                    ),
                    'mapped'=>False,
                    'required'=>False,
                ))
            ;
        }

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\FormBundle\Entity\FieldOption'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hris_formbundle_fieldoptiontype';
    }
}
