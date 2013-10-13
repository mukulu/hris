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

use Doctrine\Common\Collections\ArrayCollection;
use Hris\FieldOptionGroupToIdTransformer;
use Hris\FormBundle\Entity\FriendlyReportCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FriendlyReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('sort')
            ->add('serie')
            ->add('friendlyReportCategory','entity',array(
                    'class'=>'HrisFormBundle:FieldOptionGroup',
                    'multiple'=>True,
                    'required'=>True,
                    'mapped'=>False,
            ))
            ->add('useTargets',null,array(
                'required'=> False,
            ))
            ->add('ignoreSkipInReport',null,array(
                'required'=> False,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hris\FormBundle\Entity\FriendlyReport'
        ));
    }

    public function getName()
    {
        return 'hris_formbundle_friendlyreporttype';
    }
}
