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
namespace Hris\ReportsBundle\Twig\Extension;

use Hris\ReportsBundle\Entity\Record;

class SumExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'sum', array($this, 'sumFilter')
            ),
        );
    }

    /**
     * Converts into twig template tag {{ integerArray | sum }}
     *
     * @param array integerArray
     * @return float|integer
     */
    public function sumFilter($integerArray)
    {
        $sum = array_sum($integerArray);

        return $sum;
    }

    public function getName()
    {
        return 'sum_expression';
    }
}
