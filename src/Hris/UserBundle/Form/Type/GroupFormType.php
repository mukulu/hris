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
 * Reused from FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */

namespace Hris\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Yaml\Parser;

class GroupFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The Group class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Generates an array of roles based on roles stipulated in security configurations
     * @return mixed
     */
    private function getRoleNames()
    {
        $pathToSecurity = __DIR__ . '/../../../../..' . '/app/config/security.yml';
        $yaml = new Parser();
        $userRoles = array();
        $rolesArray = $yaml->parse(file_get_contents($pathToSecurity));
        $rolesCaptured = $rolesArray['security']['role_hierarchy'];
        //print_r($rolesCaptured);
        foreach($rolesCaptured as $key=>$value) {
            $userRoles[]=$key;
            if(!is_array($value)) {
                $userRoles[]=$value;
            }else {
                $userRoles=array_merge($userRoles,$value);
            }
        }
        $userRoles = array_unique($userRoles);
        //sort for display purposes
        asort($userRoles);
        foreach($userRoles as $key=>$value) {
            $sortedRoles[$value]=$value;
        }
        return $sortedRoles;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                    'label' => 'form.group_name',
                    'translation_domain' => 'FOSUserBundle'
                )
            )
            ->add('description',null,array(
                'required'=>false,
            ))
            ->add('roles', 'choice', array(
                'multiple'=>true,
                'choices'   => $this->getRoleNames(),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'group',
        ));
    }

    public function getName()
    {
        return 'fos_user_group';
    }
}
