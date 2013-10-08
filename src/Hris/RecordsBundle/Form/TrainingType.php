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
 * @author Ismail Y.Koleleni <ismailkoleleni@gmail.com>
 *
 */
namespace Hris\RecordsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coursename')
            ->add('courselocation')
            ->add('sponsor', 'choice', array(
                'empty_value' => '--SELECT--',
                'choices' => array(
                    'Development Partner' => 'Development Partner',
                    'Employer' => 'Employer',
                    'MOHSW' => 'Ministry of Health and Social Welfare',
                    'Self Sponsored' => 'Self Sponsored',
                    'Other' => 'Other',
                ),
            ))
            ->add('startdate', 'date', array('input' => 'datetime', 'widget' => 'single_text',) )
            ->add('enddate', 'date', array('input' => 'datetime', 'widget' => 'single_text',) )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\RecordsBundle\Entity\Training'
        ));
    }

    public function getName()
    {
        return 'hris_recordsbundle_trainingtype';
    }
}
