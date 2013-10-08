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
namespace Hris\DashboardBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class OrganisationunitToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (organisationunit) to a string (id).
     *
     * @param Organisationunit|null $organisationunit
     * @return string
     */
    public function transform($organisationunit)
    {
        if (null === $organisationunit || count($organisationunit) == 0) {
            return "";
        }

        return $organisationunit->getId();
    }

    /**
     * Transforms a string (id) to an object (organisationunit).
     *
     * @param string $id
     *
     * @return Organisationunit|null
     *
     * @throws TransformationFailedException if object (organisationunit) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }
        $organisationunit = $this->om
            ->getRepository('HrisOrganisationunitBundle:Organisationunit')
            ->findOneBy(array('id' => $id))
        ;
        if (null === $organisationunit) {
            throw new TransformationFailedException(sprintf(
                'An organisationunit with id "%s" does not exist!',
                $id
            ));
        }
        return $organisationunit;
    }
}
