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
namespace Hris\RecordsBundle\Twig\Extension;

use Hris\RecordsBundle\Entity\Record;

class CalculateFieldExpressionExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'calculateFieldExpression', array($this, 'calculateFieldExpressionFilter')
            ),
        );
    }

    /**
     * Converts into twig template tag {{ record | calculatedFieldExpression(fieldExpression) }}
     *
     * @param $record
     * @param $fieldExpression
     * @return mixed
     */
    public function calculateFieldExpressionFilter($record, $fieldExpression,$fields)
    {
        $match = NULL;
        $valueKey=NULL;
        $recordFieldKey = ucfirst(Record::getFieldKey());

        if(preg_match_all('/\#{([^\}]+)\}/',$fieldExpression,$match)) {
            foreach($fields as $fieldKey=>$field) {
                if($field->getName()==$match[1][0]) {
                    // Translates to $field->getUid()
                    // or $field->getUid() depending on value of $recordKeyName
                    $valueKey = call_user_func_array(array($field, "get${recordFieldKey}"),array());
                }
            }
        }
        $displayValue = $record->getValue($valueKey,'Y-m-d');
        $datavalue = @@str_replace($match[0][0],$displayValue,$fieldExpression);

        $calculatedExpression = eval("return $datavalue;");

        return $calculatedExpression;
    }

    public function getName()
    {
        return 'calculate_field_expression';
    }
}
