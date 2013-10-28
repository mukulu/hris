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
namespace Hris\RecordsBundle\Form;

use Hris\ReportsBundle\Form\OrganisationunitToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ValidationRunType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // assuming $entityManager is passed in options
        $em = $options['em'];
        $transformer = new OrganisationunitToIdTransformer($em);

        $builder
            ->add($builder->create('organisationunit','hidden',array(
                'required'=>True,
                'constraints'=> array(
                    new NotBlank(),
                )
                ))->addModelTransformer($transformer)
            )
            ->add('organisationunitLevel','entity', array(
                'class'=>'HrisOrganisationunitBundle:OrganisationunitLevel',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('organisationunitLevel')
                        ->orderBy('organisationunitLevel.level', 'ASC');
                },
                'constraints'=>array(
                    new NotBlank(),
                )
            ))
            ->add('validations','entity', array(
                'class'=>'HrisDataQualityBundle:Validation',
                'multiple'=>true,
                'constraints'=>array(
                    new NotBlank(),
                )
            ))
            ->add('forms','entity', array(
                'class'=>'HrisFormBundle:Form',
                'multiple'=>true,
                'constraints'=>array(
                    new NotBlank(),
                )
            ))
            ->add('submit','submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(
            array('em')
        );
        $resolver->setAllowedTypes(array(
            'em'=>'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    public function getName()
    {
        return 'hris_recordsbundle_validationtype';
    }
}
