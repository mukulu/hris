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
namespace Hris\FormBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Hris\FormBundle\Entity\Field;
use Hris\FormBundle\Entity\FieldGroup;
use Hris\FormBundle\Entity\FieldOption;
use Hris\FormBundle\Entity\FieldOptionGroup;
use Hris\FormBundle\Entity\FieldOptionGroupset;

class LoadFieldData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var fields
     */
    private $fields;

    /**
     * @var fieldOptions
     */
    private $fieldOptions;

    /**
     * @var fieldGroups
     */
    private $fieldGroups;

    /**
     * @var fieldOptionGroups
     */
    private $fieldOptionGroups;

    /**
     * @var fieldOptionGroupsets
     */
    private $fieldOptionGroupsets;

    /**
     * Returns Array of field fixtures
     *
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Returns Array of fieldOption fixtures
     *
     * @return mixed
     */
    public function getFieldOptions()
    {
        return $this->fieldOptions;
    }

    /**
     * Returns Array of fieldOptionGroups fixtures
     *
     * @return mixed
     */
    public function getFieldGroups()
    {
        return $this->fieldGroups;
    }

    /**
     * Returns Array of fieldOptionGroups fixtures
     *
     * @return fieldOptionGroups
     */
    public function getFieldOptionGroup()
    {
        return $this->fieldOptionGroups;
    }

    /**
     * Returns Array of fieldOptionGroupsets fixtures
     *
     * @return fieldOptionGroupsets
     */
    public function getFieldOptionGroupsets()
    {
        return $this->fieldOptionGroupsets;
    }

    /**
     * Returns Array of dummy fields
     *
     * @return array
     */
    public function addDummyFields()
    {
        // Load Public Data
        $this->fields = Array(
            0=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'Firstname',
                'caption'=>'First name',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's firstname (Compulsory)",
                'history'=>false),
            1=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'Middlename',
                'caption'=>'Middle name',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's middlename (Optional)",
                'history'=>false),
            2=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'Surname',
                'caption'=>'Surname',
                'compulsory'=>true,
                'fieldRelation'=>False,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'parentField'=>Null,
                'description'=>"Employee's Surname/Lastname(Compulsory)",
                'history'=>true),
            3=>Array(
                'dataType'=>'Date',
                'inputType'=>'Date',
                'name'=>'Birthdate',
                'caption'=>'Date of Birth',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Date of Birth(Compulsory)",
                'history'=>false),
            4=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Sex',
                'caption'=>'Sex',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Sex(Compulsory)",
                'history'=>false),
            5=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'MaritalStatus',
                'caption'=>'Marital Status',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Marital Status(Compulsory)",
                'history'=>true),
            6=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Nationality',
                'caption'=>'Nationality',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>true,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Nationality(Compulsory)",
                'history'=>true),
            7=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Religion',
                'caption'=>'Religion',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>true,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Religion(Compulsory)",
                'history'=>true),
            8=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'BasicEducationLevel',
                'caption'=>'Basic Education Level',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Basic Education Level(Compulsory)",
                'history'=>true),
            9=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'ProfessionEducationLevel',
                'caption'=>'Profession Education Level',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Profession Education Level(Compulsory)",
                'history'=>true),
            10=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'NumberofChildrenDependants',
                'caption'=>'Number of Children/Dependants',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Number of Children/Dependants(Optional)",
                'history'=>true),
            11=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'DistrictofDomicile',
                'caption'=>'District of Domicile',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's District of Domicile(Optional)",
                'history'=>false),
            12=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'CheckNumber',
                'caption'=>'Check Number',
                'compulsory'=>true,
                'isUnique'=>true,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Check Number(Compulsory)",
                'history'=>true),
            13=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'EmployersFileNumber',
                'caption'=>'Employer`s File Number',
                'compulsory'=>true,
                'isUnique'=>true,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Employer`s File Number(Compulsory)",
                'history'=>false),
            14=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'RegistrationNumber',
                'caption'=>'Registration Number',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Registration Number(Optional)",
                'history'=>false),
            15=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'TermsofEmployment',
                'caption'=>'Terms of Employment',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Terms of Employment(Compulsory)",
                'history'=>true),
            16=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Profession',
                'caption'=>'Profession',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>True,
                'parentField'=>Null,
                'description'=>"Employee's Profession(Compulsory)",
                'history'=>true),
            17=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'PresentDesignation',
                'caption'=>'Present Designation',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Array('Profession'),
                'description'=>"Employee's Present Designation(Compulsory)",
                'history'=>true),
            18=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'HospitalPresentDesignation',
                'caption'=>'Hospital Present Designation',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Array('Profession'),
                'description'=>"Employee's Hospital Present Designation(Compulsory)",
                'history'=>true),
            19=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'SuperlativeSubstantivePosition',
                'caption'=>'Superlative Substantive Position',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Superlative Substantive Position(Compulsory)",
                'history'=>true),
            20=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Department',
                'caption'=>'Department',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Department(Compulsory)",
                'history'=>true),
            21=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'SalaryScale',
                'caption'=>'Salary Scale',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Salary Scale(Compulsory)",
                'history'=>true),
            22=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'MonthlyBasicSalary',
                'caption'=>'Monthly Basic Salary',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Monthly Basic Salary(Optional)",
                'history'=>true),
            23=>Array(
                'dataType'=>'Date',
                'inputType'=>'Date',
                'name'=>'DateofFirstAppointment',
                'caption'=>'Date of First Appointment',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Date of First Appointment(Compulsory)",
                'history'=>false),
            24=>Array(
                'dataType'=>'Date',
                'inputType'=>'Date',
                'name'=>'DateofConfirmation',
                'caption'=>'Date of Confirmation',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Date of Confirmation(Optional)",
                'history'=>false),
            25=>Array(
                'dataType'=>'Date',
                'inputType'=>'Date',
                'name'=>'DateofLastPromotion',
                'caption'=>'Date of Last Promotion',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Date of Last Promotion(Optional)",
                'history'=>true),
            26=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Employer',
                'caption'=>'Employer',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Employer(Compulsory)",
                'history'=>true),
            27=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'EmploymentStatus',
                'caption'=>'Employment Status',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Employment Status(Compulsory)",
                'history'=>true),
            28=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'RegisteredDisability',
                'caption'=>'Registered Disability',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Legal Registered Disability(Optional)",
                'history'=>false),
            29=>Array(
                'dataType'=>'String',
                'inputType'=>'TextArea',
                'name'=>'ContactsofEmployee',
                'caption'=>'Contacts of Employee',
                'compulsory'=>true,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Contacts of Employee(Compulsory)",
                'history'=>false),
            30=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'NextofKin',
                'caption'=>'Next of Kin',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Next of Kin(Optional)",
                'history'=>true),
            31=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'RelationshiptoNextofKin',
                'caption'=>'Relationship to Next of Kin',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Relationship to Next of Kin(Optional)",
                'history'=>true),
            32=>Array(
                'dataType'=>'String',
                'inputType'=>'TextArea',
                'name'=>'ContactsofNextofKin',
                'caption'=>'Contacts of Next of Kin',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>false,
                'skipInReport'=>false,
                'calculatedExpression'=>'',
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Contacts of Next of Kin(Optional)",
                'history'=>true),
            33=>Array(
                'dataType'=>'String',
                'inputType'=>'TextArea',
                'name'=>'EmploymentDistribution',
                'caption'=>'Employment Distribution',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>true,
                'skipInReport'=>false,
                'calculatedExpression'=>"date('Y', strtotime(#{DateofFirstAppointment}))",
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Contacts of Next of Kin(Optional)",
                'history'=>true),
            34=>Array(
                'dataType'=>'String',
                'inputType'=>'TextArea',
                'name'=>'RetirementDistribution',
                'caption'=>'Retirement Distribution',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>true,
                'skipInReport'=>false,
                'calculatedExpression'=>"date('Y', strtotime('#{Birthdate}'))+60",
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Contacts of Next of Kin(Optional)",
                'history'=>true),
            35=>Array(
                'dataType'=>'String',
                'inputType'=>'TextArea',
                'name'=>'AgeDistribution',
                'caption'=>'Age Distribution',
                'compulsory'=>false,
                'isUnique'=>false,
                'isCalculated'=>true,
                'skipInReport'=>false,
                'calculatedExpression'=>"((floor(floor((time() - strtotime('#{Birthdate}')) / 31556926)/5))*5) .'-'.(((floor(floor((time() - strtotime('#{Birthdate}')) / 31556926)/5))*5)+4)",
                'fieldRelation'=>False,
                'parentField'=>Null,
                'description'=>"Employee's Contacts of Next of Kin(Optional)",
                'history'=>true),
        );
        return $this->fields;
    }

    /**
     * Returns Array of dummy fieldOptions
     *
     * @return array
     */
    public function addDummyFieldOptions()
    {
        // Load Public Data
        $this->fieldOptions = Array(
            // Gender options
            0=>Array(
                'value'=>'Male',
                'description'=>'Employee`s Gender',
                'field'=>  'Sex-field',
                'skipinreport'=>False,
                'sort' => 1),
            1=>Array(
                'value'=>'Female',
                'description'=>'Employee`s Gender',
                'field'=>  'Sex-field',
                'skipinreport'=>False,
                'sort' => 2),
            // Marital status options
            2=>Array(
                'value'=>'Single',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'skipinreport'=>False,
                'sort' => 1),
            3=>Array(
                'value'=>'Married',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'skipinreport'=>False,
                'sort' => 2),
            4=>Array(
                'value'=>'Separated',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'skipinreport'=>False,
                'sort' => 3),
            5=>Array(
                'value'=>'Divorced',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'skipinreport'=>False,
                'sort' => 4),
            6=>Array(
                'value'=>'Widow',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'skipinreport'=>False,
                'sort' => 5),
            7=>Array(
                'value'=>'Widower',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'skipinreport'=>False,
                'sort' => 6),
            // Nationality options
            8=>Array(
                'value'=>'Tanzanian',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'skipinreport'=>False,
                'sort'=> 1),
            9=>Array(
                'value'=>'Kenyan',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'skipinreport'=>False,
                'sort'=> 2),
            10=>Array(
                'value'=>'Ugandan',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'skipinreport'=>False,
                'sort'=> 3),
            11=>Array(
                'value'=>'Cuban',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'skipinreport'=>False,
                'sort'=> 4),
            12=>Array(
                'value'=>'Somalian',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'skipinreport'=>False,
                'sort'=> 5),
            13=>Array(
                'value'=>'Afghanistan',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'skipinreport'=>False,
                'sort'=> 6),
            // Religion options
            14=>Array(
                'value'=>'Atheist',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'skipinreport'=>False,
                'sort'=> 1,),
            15=>Array(
                'value'=>'Buddha',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'skipinreport'=>False,
                'sort'=> 2),
            16=>Array(
                'value'=>'Christian',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'skipinreport'=>False,
                'sort'=> 3),
            17=>Array(
                'value'=>'Hindu',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'skipinreport'=>False,
                'sort'=> 4),
            18=>Array(
                'value'=>'Islam',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'skipinreport'=>False,
                'sort'=> 5),
            19=>Array(
                'value'=>'Judaism',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'skipinreport'=>False,
                'sort'=> 6),
            // Basic education level options
            20=>Array(
                'value'=>'Ordinary Secondary Education',
                'description'=>'Employee`s Basic Education Level',
                'field'=>  'BasicEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 1),
            21=>Array(
                'value'=>'Advanced Secondary Education',
                'description'=>'Employee`s Basic Education Level',
                'field'=>  'BasicEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 2),
            22=>Array(
                'value'=>'Primary Education',
                'description'=>'Employee`s Basic Education Level',
                'field'=>  'BasicEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 3),
            // Profession education level options
            23=>Array(
                'value'=>'None',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 1),
            24=>Array(
                'value'=>'Certificate',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 2),
            25=>Array(
                'value'=>'Advanced Diploma',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 3),
            26=>Array(
                'value'=>'Postgraduate Diploma',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 4),
            27=>Array(
                'value'=>'Bachelor Degree',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 5),
            28=>Array(
                'value'=>'Masters Degree',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 6),
            29=>Array(
                'value'=>'PhD',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'skipinreport'=>False,
                'sort'=> 7),
            // Terms of employement options
            30=>Array(
                'value'=>'Permanent and Pensionable',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'skipinreport'=>False,
                'sort'=> 1),
            31=>Array(
                'value'=>'Contract',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'skipinreport'=>False,
                'sort'=> 2),
            32=>Array(
                'value'=>'Operational Service',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'skipinreport'=>False,
                'sort'=> 3),
            33=>Array(
                'value'=>'Volunteer',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'skipinreport'=>False,
                'sort'=> 4),
            34=>Array(
                'value'=>'Temporarily',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'skipinreport'=>False,
                'sort'=> 5),
            35=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'skipinreport'=>False,
                'sort'=> 6),
            // Profession options
            36=>Array(
                'value'=>'Medical Doctor',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'skipinreport'=>False,
                'sort'=> 1),
            37=>Array(
                'value'=>'Pharmacist',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'skipinreport'=>False,
                'sort'=> 2),
            38=>Array(
                'value'=>'Environmental Health Officer',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'skipinreport'=>False,
                'sort'=> 3),
            39=>Array(
                'value'=>'Assistant Medical Officer',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'skipinreport'=>False,
                'sort'=> 4),
            40=>Array(
                'value'=>'Nursing Officer',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'skipinreport'=>False,
                'sort'=> 5),
            41=>Array(
                'value'=>'Dental Technologist',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'skipinreport'=>False,
                'sort'=> 6),
            42=>Array(
                'value'=>'Clinical Assistant',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'skipinreport'=>False,
                'sort'=> 7),
            43=>Array(
                'value'=>'Driver',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'skipinreport'=>False,
                'sort'=> 8),
            //Present Designation options
            44=>Array(
                'value'=>'Medical Doctor I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 1),
            45=>Array(
                'value'=>'Medical Doctor II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 2),
            46=>Array(
                'value'=>'Principal Medical Doctor I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 3),
            47=>Array(
                'value'=>'Principal Medical Doctor II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 4),
            48=>Array(
                'value'=>'Senior Medical Doctor',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 5),
            49=>Array(
                'value'=>'Nursing Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 1),
            59=>Array(
                'value'=>'Nursing Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 2),
            60=>Array(
                'value'=>'Principal Nursing Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 3),
            61=>Array(
                'value'=>'Principal Nursing Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 4),
            62=>Array(
                'value'=>'Senior Nursing Officer',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 5),
            63=>Array(
                'value'=>'Assistant Medical Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 1),
            64=>Array(
                'value'=>'Assistant Medical Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>'PresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 2),
            65=>Array(
                'value'=>'Principal Assistant Medical Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 3),
            66=>Array(
                'value'=>'Principal Assistant Medical Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 4),
            67=>Array(
                'value'=>'Senior Assistant Medical Officer',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 5),
            68=>Array(
                'value'=>'Driver I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Driver',
                'skipinreport'=>False,
                'sort' => 1),
            69=>Array(
                'value'=>'Driver II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Driver',
                'skipinreport'=>False,
                'sort' => 2),
            70=>Array(
                'value'=>'Driver III',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Driver',
                'skipinreport'=>False,
                'sort' => 3),
            71=>Array(
                'value'=>'Pharmacist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 1),
            72=>Array(
                'value'=>'Pharmacist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 2),
            73=>Array(
                'value'=>'Principal Pharmacist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 3),
            74=>Array(
                'value'=>'Principal Pharmacist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 4),
            75=>Array(
                'value'=>'Senior Pharmacist',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 5),
            76=>Array(
                'value'=>'Dental Technologist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 1),
            77=>Array(
                'value'=>'Dental Technologist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 2),
            78=>Array(
                'value'=>'Principal Dental Technologist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 3),
            79=>Array(
                'value'=>'Principal Dental Technologist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 4),
            80=>Array(
                'value'=>'Senior Dental Technologist',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 5),
            81=>Array(
                'value'=>'Environmental Health Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 1),
            82=>Array(
                'value'=>'Environmental Health Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 2),
            83=>Array(
                'value'=>'Principal Environmental Health Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 3),
            84=>Array(
                'value'=>'Principal Environmental Health Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 4),
            85=>Array(
                'value'=>'Senior Environmental Health Officer',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 5),
            86=>Array(
                'value'=>'Clinical Assistant',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Clinical Assistant',
                'skipinreport'=>False,
                'sort' => 1),
            87=>Array(
                'value'=>'Principal Clinical Assistant',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Clinical Assistant',
                'skipinreport'=>False,
                'sort' => 2),
            88=>Array(
                'value'=>'Senior Clinical Assistant',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'parentFieldOption'=>'Clinical Assistant',
                'skipinreport'=>False,
                'sort' => 3),
            // Superlative Substantive Position options
            89=>Array(
                'value'=>'Chief Medical Officer',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 1),
            90=>Array(
                'value'=>'Chief Accountant',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 2),
            91=>Array(
                'value'=>'Chief Nursing Officer',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 3),
            92=>Array(
                'value'=>'Director General',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 4),
            93=>Array(
                'value'=>'Programme Manager',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 5),
            94=>Array(
                'value'=>'Pharmaceutical Advisor',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 7),
            95=>Array(
                'value'=>'Director of Human Resources',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 8),
            96=>Array(
                'value'=>'Director, Medicine and Costmetics',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 9),
            97=>Array(
                'value'=>'Director',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 10),
            98=>Array(
                'value'=>'Registrar',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 11),
            99=>Array(
                'value'=>'Deputy Registrar',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 12),
            100=>Array(
                'value'=>'Assistant Director',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 13),
            101=>Array(
                'value'=>'District Executive Director',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 14),
            102=>Array(
                'value'=>'Regional Medical Officer',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 15),
            103=>Array(
                'value'=>'District Medical Officer',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 16),
            104=>Array(
                'value'=>'Regional and District Coordinator',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 17),
            105=>Array(
                'value'=>'Programme Coordinator',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 18),
            106=>Array(
                'value'=>'Hospital Management Team',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 19),
            107=>Array(
                'value'=>'RHMT Co-Opted Member',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 20),
            108=>Array(
                'value'=>'RHMT Member',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 21),
            109=>Array(
                'value'=>'CHMT Co-Opted Member',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 22),
            110=>Array(
                'value'=>'CHMT Member',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 23),
            111=>Array(
                'value'=>'Head of Department',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 24),
            112=>Array(
                'value'=>'Head of Facility',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 25),
            1112=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 26),
            113=>Array(
                'value'=>'None',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'skipinreport'=>False,
                'sort'=> 27),
            // Department options
            114=>Array(
                'value'=>'Curative Services',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 1),
            115=>Array(
                'value'=>'Preventive Service',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 2),
            116=>Array(
                'value'=>'Administration',
                'description'=>'Employee`s Departmentn',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 3),
            117=>Array(
                'value'=>'OutPatient Department',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 4),
            1118=>Array(
                'value'=>'Pharmacy',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 5),
            119=>Array(
                'value'=>'Obsy and Gynae',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 6),
            120=>Array(
                'value'=>'Psychiatric',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 7),
            121=>Array(
                'value'=>'Optometry',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 8),
            122=>Array(
                'value'=>'Mortuary',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 9),
            123=>Array(
                'value'=>'Pediatric',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 10),
            124=>Array(
                'value'=>'X-Ray Department',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 11),
            125=>Array(
                'value'=>'Theatre',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 12),
            126=>Array(
                'value'=>'Ophtamology',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 13),
            127=>Array(
                'value'=>'Tuberculosis',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 14),
            128=>Array(
                'value'=>'Dermatology',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 15),
            129=>Array(
                'value'=>'Dental',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 16),
            130=>Array(
                'value'=>'Laboratory',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 17),
            131=>Array(
                'value'=>'Orthopaedics',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 18),
            132=>Array(
                'value'=>'Pathology',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 19),
            133=>Array(
                'value'=>'Surgical Department',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 20),
            134=>Array(
                'value'=>'Transport',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 21),
            135=>Array(
                'value'=>'Security',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 22),
            136=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'skipinreport'=>False,
                'sort'=> 23),
            // Salary scale options
            137=>Array(
                'value'=>'TGHS. A',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 1),
            138=>Array(
                'value'=>'TGHS. B',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 2),
            139=>Array(
                'value'=>'TGHS. C',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 3),
            140=>Array(
                'value'=>'TGHS. D',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 4),
            141=>Array(
                'value'=>'TGHS. E',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 5),
            142=>Array(
                'value'=>'TGHS. F',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 6),
            143=>Array(
                'value'=>'TGHS. G',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 7),
            144=>Array(
                'value'=>'TGHS. H',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 8),
            145=>Array(
                'value'=>'TGHS. I',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 9),
            146=>Array(
                'value'=>'TGHS. J',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 10),
            147=>Array(
                'value'=>'TGHS. K',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 11),
            148=>Array(
                'value'=>'TGHS. L',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 12),
            149=>Array(
                'value'=>'TGHOS. A',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 13),
            150=>Array(
                'value'=>'TGHOS. B',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 14),
            151=>Array(
                'value'=>'TGHOS. C',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 15),
            152=>Array(
                'value'=>'TGHOS. D',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 16),
            153=>Array(
                'value'=>'TGS. A',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 17),
            154=>Array(
                'value'=>'TGS. B',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 18),
            155=>Array(
                'value'=>'TGS. C',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 19),
            156=>Array(
                'value'=>'TGS. D',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 20),
            157=>Array(
                'value'=>'TGS. E',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 21),
            158=>Array(
                'value'=>'TGS. F',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 22),
            159=>Array(
                'value'=>'TGS. G',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 23),
            160=>Array(
                'value'=>'TGS. H',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 24),
            161=>Array(
                'value'=>'TGS. I',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 25),
            162=>Array(
                'value'=>'TGS. K',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 26),
            163=>Array(
                'value'=>'TGS. L',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'skipinreport'=>False,
                'sort'=> 27),
            // Employer options
            164=>Array(
                'value'=>'Ministry of Health and Social Welfare',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 1),
            165=>Array(
                'value'=>'Prime Minister Office - RALG',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 2),
            166=>Array(
                'value'=>'President Office- Public Service Management',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 3),
            167=>Array(
                'value'=>'Regional Administrative Secretary',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 4),
            168=>Array(
                'value'=>'Municipal Director',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 5),
            169=>Array(
                'value'=>'Town Director',
                'description'=>'Employer`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 6),
            170=>Array(
                'value'=>'City Council Director',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 7),
            171=>Array(
                'value'=>'District Executive Director',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 8),
            172=>Array(
                'value'=>'Good Samaritan Foundation',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 9),
            173=>Array(
                'value'=>'Faith Based Organisation',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 10),
            174=>Array(
                'value'=>'Parastatal',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 11),
            175=>Array(
                'value'=>'Private',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 12),
            176=>Array(
                'value'=>'Army',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 13),
            177=>Array(
                'value'=>'Tanzania Food and Drug Authority',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 14),
            178=>Array(
                'value'=>'Tanzania Food and Nutrition Center',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 15),
            179=>Array(
                'value'=>'Muhimbili Orthopaedic Institute',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 16),
            180=>Array(
                'value'=>'Muhimbili National Hospital',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 17),
            181=>Array(
                'value'=>'Ocean Road Cancer Institute',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 18),
            182=>Array(
                'value'=>'Pharmacy Council',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 19),
            183=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'skipinreport'=>False,
                'sort'=> 20),
            //Employment Status options
            184=>Array(
                'value'=>'On Duty',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>False,
                'sort'=> 1),
            185=>Array(
                'value'=>'Retired',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>True,
                'sort'=> 2),
            186=>Array(
                'value'=>'Transfered',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>True,
                'sort'=> 3),
            187=>Array(
                'value'=>'Off Duty',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>True,
                'sort'=> 4),
            188=>Array(
                'value'=>'On Leave Without Payment',
                'description'=>'Employee`s Employer',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>False,
                'sort'=> 5),
            189=>Array(
                'value'=>'Deceased',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>True,
                'sort'=> 6),
            190=>Array(
                'value'=>'On Secondment',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>False,
                'sort'=> 7),
            191=>Array(
                'value'=>'On Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>False,
                'sort'=> 8),
            192=>Array(
                'value'=>'On Study Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>False,
                'sort'=> 9),
            193=>Array(
                'value'=>'On Maternity Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>False,
                'sort'=> 10),
            194=>Array(
                'value'=>'On Annual Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>False,
                'sort'=> 11),
            195=>Array(
                'value'=>'On Sick Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>False,
                'sort'=> 12),
            196=>Array(
                'value'=>'Resigned',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>True,
                'sort'=> 13),
            197=>Array(
                'value'=>'Abscondent',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'skipinreport'=>True,
                'sort'=> 14),
            //Registered disability options
            198=>Array(
                'value'=>'Physical Disability',
                'description'=>'Employee`s Registered Disability',
                'field'=>  'RegisteredDisability-field',
                'skipinreport'=>False,
                'sort'=> 1),
            199=>Array(
                'value'=>'Visual Impaired',
                'description'=>'Employee`s Registered Disability',
                'field'=>  'RegisteredDisability-field',
                'skipinreport'=>False,
                'sort'=> 2),
            200=>Array(
                'value'=>'None',
                'description'=>'Employee`s Registered Disability',
                'field'=>  'RegisteredDisability-field',
                'skipinreport'=>False,
                'sort'=> 3),
            201=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Registered Disability',
                'field'=>  'RegisteredDisability-field',
                'skipinreport'=>False,
                'sort'=> 4),
            // Relationship to Next of Kin options
            202=>Array(
                'value'=>'Superior of Congregation',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'skipinreport'=>False,
                'sort'=> 1),
            203=>Array(
                'value'=>'Child',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'skipinreport'=>False,
                'sort'=> 2),
            204=>Array(
                'value'=>'Sibling',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'skipinreport'=>False,
                'sort'=> 3),
            205=>Array(
                'value'=>'Parent',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'skipinreport'=>False,
                'sort'=> 4),
            206=>Array(
                'value'=>'Spouse',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'skipinreport'=>False,
                'sort'=> 5),
            207=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'skipinreport'=>False,
                'sort'=> 6),
            //HospitalPresent Designation options
            208=>Array(
                'value'=>'Medical Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 1),
            209=>Array(
                'value'=>'Medical Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 2),
            209=>Array(
                'value'=>'Medical Officer III',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 3),
            210=>Array(
                'value'=>'Principal Medical Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 4),
            211=>Array(
                'value'=>'Principal Medical Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 5),
            212=>Array(
                'value'=>'Senior Medical Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 6),
            212=>Array(
                'value'=>'Senior Medical Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 7),
            212=>Array(
                'value'=>'Senior Medical Officer III',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Medical Doctor',
                'skipinreport'=>False,
                'sort' => 8),
            213=>Array(
                'value'=>'Nursing Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 1),
            214=>Array(
                'value'=>'Nursing Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 2),
            215=>Array(
                'value'=>'Principal Nursing Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 3),
            216=>Array(
                'value'=>'Principal Nursing Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 4),
            217=>Array(
                'value'=>'Senior Nursing Officer',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Nursing Officer',
                'skipinreport'=>False,
                'sort' => 5),
            218=>Array(
                'value'=>'Assistant Medical Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 1),
            219=>Array(
                'value'=>'Assistant Medical Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 2),
            220=>Array(
                'value'=>'Principal Assistant Medical Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 3),
            221=>Array(
                'value'=>'Principal Assistant Medical Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 4),
            222=>Array(
                'value'=>'Senior Assistant Medical Officer',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Assistant Medical Officer',
                'skipinreport'=>False,
                'sort' => 5),
            223=>Array(
                'value'=>'Driver I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Driver',
                'skipinreport'=>False,
                'sort' => 1),
            224=>Array(
                'value'=>'Driver II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Driver',
                'skipinreport'=>False,
                'sort' => 2),
            225=>Array(
                'value'=>'Driver III',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Driver',
                'skipinreport'=>False,
                'sort' => 3),
            226=>Array(
                'value'=>'Pharmacist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 1),
            227=>Array(
                'value'=>'Pharmacist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 2),
            228=>Array(
                'value'=>'Principal Pharmacist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 3),
            229=>Array(
                'value'=>'Principal Pharmacist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 4),
            230=>Array(
                'value'=>'Senior Pharmacist',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Pharmacist',
                'skipinreport'=>False,
                'sort' => 5),
            231=>Array(
                'value'=>'Dental Technologist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 1),
            232=>Array(
                'value'=>'Dental Technologist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 2),
            233=>Array(
                'value'=>'Principal Dental Technologist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 3),
            234=>Array(
                'value'=>'Principal Dental Technologist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 4),
            235=>Array(
                'value'=>'Senior Dental Technologist',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Dental Technologist',
                'skipinreport'=>False,
                'sort' => 5),
            236=>Array(
                'value'=>'Environmental Health Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 1),
            237=>Array(
                'value'=>'Environmental Health Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 2),
            238=>Array(
                'value'=>'Principal Environmental Health Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 3),
            239=>Array(
                'value'=>'Principal Environmental Health Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 4),
            240=>Array(
                'value'=>'Senior Environmental Health Officer',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Environmental Health Officer',
                'skipinreport'=>False,
                'sort' => 5),
            241=>Array(
                'value'=>'Clinical Assistant',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Clinical Assistant',
                'skipinreport'=>False,
                'sort' => 1),
            242=>Array(
                'value'=>'Principal Clinical Assistant',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Clinical Assistant',
                'skipinreport'=>False,
                'sort' => 2),
            243=>Array(
                'value'=>'Senior Clinical Assistant',
                'description'=>'Employee`s Present Designation',
                'field'=>  'HospitalPresentDesignation-field',
                'parentFieldOption'=>'Clinical Assistant',
                'skipinreport'=>False,
                'sort' => 3),
        );
        return $this->fieldOptions;
    }

    /**
     * Returns Array of dummy fieldOptionGroups
     *
     * @return array
     */
    public function addDummyFieldOptionGroups()
    {
        // Load dummy field option groups for indicators and friendly/generic reports
        $this->fieldOptionGroups = Array(
            0=>Array(
                'name'=>'Atleast Secondary Education',
                'field'=>'BasicEducationLevel-field',
                'description'=>"Primary and Secondary Education",
                'options'=> Array(
                    0=> strtolower(str_replace(' ','','Ordinary Secondary Education')).'BasicEducationLevel-fieldoption',
                    1=> strtolower(str_replace(' ','','Primary Education')).'BasicEducationLevel-fieldoption',
                ),
            ),
            1=>Array(
                'name'=>'University Education',
                'field'=>'ProfessionEducationLevel-field',
                'description'=>"Bachelor Degree, Masters and Phd",
                'options'=> Array(
                    0=> strtolower(str_replace(' ','','Postgraduate Diploma')).'ProfessionEducationLevel-fieldoption',
                    1=> strtolower(str_replace(' ','','Bachelor Degree')).'ProfessionEducationLevel-fieldoption',
                    2=> strtolower(str_replace(' ','','Masters Degree')).'ProfessionEducationLevel-fieldoption',
                    3=> strtolower(str_replace(' ','','PhD')).'ProfessionEducationLevel-fieldoption'
                ),
            ),
            2=>Array(
                'name'=>'CCHP Attrition',
                'field'=>'EmploymentStatus-field',
                'description'=>"Comprehensive Council Health Plan Attrition Options",
                'options'=> Array(
                    0=> strtolower(str_replace(' ','','Abscondent')).'EmploymentStatus-fieldoption',
                    1=> strtolower(str_replace(' ','','Deceased')).'EmploymentStatus-fieldoption',
                    2=> strtolower(str_replace(' ','','Retired')).'EmploymentStatus-fieldoption',
                    3=> strtolower(str_replace(' ','','Transfered')).'EmploymentStatus-fieldoption'
                ),
            ),
            3=>Array(
                'name'=>'CCHP CoreAndCoopted',
                'field'=>'SuperlativeSubstantivePosition-field',
                'description'=>"Comprehensive Council Health Plan Core and Co-opted member Options",
                'options'=> Array(
                    0=> strtolower(str_replace(' ','','CHMT Co-Opted Member')).'SuperlativeSubstantivePosition-fieldoption',
                    1=> strtolower(str_replace(' ','','CHMT Member')).'SuperlativeSubstantivePosition-fieldoption',
                    2=> strtolower(str_replace(' ','','Head of Department')).'SuperlativeSubstantivePosition-fieldoption',
                    3=> strtolower(str_replace(' ','','Head of Facility')).'SuperlativeSubstantivePosition-fieldoption',
                    4=> strtolower(str_replace(' ','','Hospital Management Team')).'SuperlativeSubstantivePosition-fieldoption',
                    5=> strtolower(str_replace(' ','','Programme Coordinator')).'SuperlativeSubstantivePosition-fieldoption',
                    6=> strtolower(str_replace(' ','','RHMT Co-Opted Member')).'SuperlativeSubstantivePosition-fieldoption',
                    7=> strtolower(str_replace(' ','','RHMT Member')).'SuperlativeSubstantivePosition-fieldoption',
                    8=> strtolower(str_replace(' ','','Regional Medical Officer')).'SuperlativeSubstantivePosition-fieldoption'
                ),
            ),
            4=>Array(
                'name'=>'CCHP Professions',
                'field'=>'Profession-field',
                'description'=>"Comprehensive Council Health Plan Profession Options",
                'options'=> Array(
                    0=> strtolower(str_replace(' ','','Assistant Medical Officer')).'Profession-fieldoption',
                    1=> strtolower(str_replace(' ','','Clinical Assistant')).'Profession-fieldoption',
                    2=> strtolower(str_replace(' ','','Dental Technologist')).'Profession-fieldoption',
                    3=> strtolower(str_replace(' ','','Environmental Health Officer')).'Profession-fieldoption',
                    4=> strtolower(str_replace(' ','','Medical Doctor')).'Profession-fieldoption',
                    5=> strtolower(str_replace(' ','','Nursing Officer')).'Profession-fieldoption',
                    6=> strtolower(str_replace(' ','','Pharmacist')).'Profession-fieldoption'
                ),
            ),
            5=>Array(
                'name'=>'CCHP Staff Availability',
                'field'=>  'EmploymentStatus-field',
                'description'=>"Comprehensive Council Health Plan Staff Availability Options",
                'options'=> Array(
                    0=> strtolower(str_replace(' ','','Abscondent')).'EmploymentStatus-fieldoption',
                    1=> strtolower(str_replace(' ','','Deceased')).'EmploymentStatus-fieldoption',
                    2=> strtolower(str_replace(' ','','Retired')).'EmploymentStatus-fieldoption',
                    3=> strtolower(str_replace(' ','','Transfered')).'EmploymentStatus-fieldoption',
                    5=> strtolower(str_replace(' ','','Off Duty')).'EmploymentStatus-fieldoption',
                    6=> strtolower(str_replace(' ','','On Annual Leave')).'EmploymentStatus-fieldoption',
                    7=> strtolower(str_replace(' ','','On Duty')).'EmploymentStatus-fieldoption',
                    8=> strtolower(str_replace(' ','','On Leave')).'EmploymentStatus-fieldoption',
                    9=> strtolower(str_replace(' ','','On Leave Without Payment')).'EmploymentStatus-fieldoption',
                    10=> strtolower(str_replace(' ','','On Maternity Leave')).'EmploymentStatus-fieldoption',
                    11=> strtolower(str_replace(' ','','On Secondment')).'EmploymentStatus-fieldoption',
                    12=> strtolower(str_replace(' ','','On Study Leave')).'EmploymentStatus-fieldoption',
                    13=> strtolower(str_replace(' ','','On Sick Leave')).'EmploymentStatus-fieldoption'
                ),
            ),
            6=>Array(
                'name'=>'Health Professions',
                'field'=>'Profession-field',
                'description'=>"Health Sector Professions",
                'options'=> Array(
                    0=> strtolower(str_replace(' ','','Medical Doctor')).'Profession-fieldoption',
                    1=> strtolower(str_replace(' ','','Pharmacist')).'Profession-fieldoption',
                    2=> strtolower(str_replace(' ','','Environmental Health Officer')).'Profession-fieldoption',
                    3=> strtolower(str_replace(' ','','Assistant Medical Officer')).'Profession-fieldoption',
                    4=> strtolower(str_replace(' ','','Nursing Officer')).'Profession-fieldoption',
                    5=> strtolower(str_replace(' ','','Dental Technologist')).'Profession-fieldoption',
                    6=> strtolower(str_replace(' ','','Clinical Assistant')).'Profession-fieldoption'
                ),
            ),
            7=>Array(
                'name'=>'Gender',
                'field'=>  'Sex-field',
                'description'=>"Employee`s gender",
                'options'=> Array(
                    0=> strtolower(str_replace(' ','','Female')).'Sex-fieldoption',
                    1=> strtolower(str_replace(' ','','Male')).'Sex-fieldoption'
                ),
            ),

        );
        return $this->fieldOptionGroups;
    }

    /**
     * Returns Array of dummy fieldGroups
     *
     * @return array
     */
    public function addDummyFieldGroups()
    {
        // Load Public Data
        $this->fieldGroups = Array(
            0=>Array(
                'name'=>'Compulsory Fields',
                'description'=>"Fields who's value must be filled before form submission"),
            1=>Array(
                'name'=>'Unique Fields',
                'description'=>"Fields who's value must be unique for all records"),
            2=>Array(
                'name'=>'Visible Fields',
                'description'=>"Fields that must be displayed for any employee's reocrd"),
            3=>Array(
                'name'=>'Combo Fields',
                'description'=>"Fields with combo options"),
        );
        return $this->fieldGroups;
    }

    /**
     * Returns Array of dummy fieldGroupsets
     *
     * @return array
     */
    public function addDummyFieldGroupsets()
    {
        // Load Public Data
        $this->fieldOptionGroupsets = Array(
            0=>Array(
                'name'=>'Compulsory Options',
                'description'=>"Fields who's value must be filled before form submission"),
            1=>Array(
                'name'=>'History Options',
                'description'=>"Fields who's value must be unique for all records")
        );
        return $this->fieldGroups;
    }

    /**
     * Loads metadata into the database
     *
     * @param ObjectManager $manager
     */

    public function load(ObjectManager $manager)
	{
        $this->addDummyFields();
        $this->addDummyFieldOptions();
        $this->addDummyFieldOptionGroups();
        $this->addDummyFieldGroups();
        $this->addDummyFieldGroupsets();

        // Create FieldOptionGroupset for later OptionGroups assignment
        foreach($this->fieldOptionGroupsets as $fieldOptionGroupsetKey=>$humanResourceFieldOptionGroupsets) {
            $fieldOptionGroupset = new FieldOptionGroupset();
            $fieldOptionGroupset->setName($humanResourceFieldOptionGroupsets['name']);
            $fieldOptionGroupset->setDescription($humanResourceFieldOptionGroupsets['description']);
            $fieldOptionGroupsetReference = strtolower(str_replace(' ','',$humanResourceFieldOptionGroupsets['name'])).'-fieldoptiongroupset';
            $this->addReference($fieldOptionGroupsetReference, $fieldOptionGroupset);
            $manager->persist($fieldOptionGroupset);
        }

        // Populate dummy fields
		foreach($this->fields as $fieldKey=>$humanResourceField) {
			$field = new Field();
			$field->setDataType( $manager->merge($this->getReference(strtolower($humanResourceField['dataType']). '-datatype')));
			$field->setInputType($manager->merge($this->getReference(strtolower($humanResourceField['inputType']).'-inputtype')));
			$field->setName($humanResourceField['name']);
			$field->setCaption($humanResourceField['caption']);
			$field->setDescription($humanResourceField['description']);
			$field->setHashistory($humanResourceField['history']);
            $field->setFieldrelation($humanResourceField['fieldRelation']);
			$field->setCompulsory($humanResourceField['compulsory']);
            $field->setIsUnique($humanResourceField['isUnique']);
            $field->setIsCalculated($humanResourceField['isCalculated']);
            $field->setSkipInReport($humanResourceField['skipInReport']);
            $field->setCalculatedExpression($humanResourceField['calculatedExpression']);
            if(!empty($humanResourceField['parentField'])) {
                foreach($humanResourceField['parentField'] as $parentField) {
                    $parentFieldByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$parentField)).'-field' ));
                    $field->addParentField($parentFieldByReference);
                }
            }
            $fieldReference = strtolower(str_replace(' ','',$humanResourceField['name'])).'-field';
			$this->addReference($fieldReference, $field);
            $manager->persist($field);
            // Append  dummy options for input type of select.
            if($humanResourceField['inputType'] == 'Select') {
                //Create Field Option Group by Field
                $fieldOptionGroup = new FieldOptionGroup();
                $fieldOptionGroup->setName($humanResourceField['name']);
                $fieldOptionGroup->setField($field);
                $fieldOptionGroup->setDescription($humanResourceField['description']);
                $fieldOptionGroupReference = strtolower(str_replace(' ','',$humanResourceField['name'])).'-fieldoptiongroup';
                $this->addReference($fieldOptionGroupReference, $fieldOptionGroup);

                // Assign field options to their fields & field  option group
                foreach($this->fieldOptions as $fieldOptionKey=> $humanResourceFieldOptions) {
                    // Options are assigned to option groups according to field names(grouping of option by fields they belong to)
                    if(str_replace('-field','',$humanResourceFieldOptions['field']) == str_replace(' ','',$humanResourceField['name']) ) {
                        $fieldOption = new FieldOption();
                        $fieldOption->setField( $manager->merge($this->getReference($fieldReference)) );
                        $fieldOption->setSort($humanResourceFieldOptions['sort']);
                        $fieldOption->setValue($humanResourceFieldOptions['value']);
                        $fieldOption->setDescription($humanResourceFieldOptions['description']);
                        $fieldOption->setSkipInReport($humanResourceFieldOptions['skipinreport']);
                        // If it has parent Option add it
                        if(isset($humanResourceFieldOptions['parentFieldOption']) && !empty($humanResourceFieldOptions['parentFieldOption'])) {
                            // Workout parent field for child option, through parent field of current field option's field.
                            $childFieldByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$humanResourceFieldOptions['field']))));
                            $parentField = $childFieldByReference->getParentField()->getValues();
                            //Workout reference of parent option(note: reference is concatentaion of option value and field name, hence need for parentfieldName)
                            $parentFieldOptionReference = strtolower(str_replace(' ','',$humanResourceFieldOptions['parentFieldOption'])).str_replace('-field','',$parentField[0]->getName()).'-fieldoption';
                            $parentFieldOptionByReference = $manager->merge($this->getReference( $parentFieldOptionReference ));
                            $manager->persist($parentFieldOptionByReference);
                            $fieldOption->addParentFieldOption($parentFieldOptionByReference);

                        }
                        $fieldOptionReference = strtolower(str_replace(' ','',$humanResourceFieldOptions['value'])).str_replace('-field','',$humanResourceFieldOptions['field']).'-fieldoption';
                        $this->addReference($fieldOptionReference, $fieldOption);
                        $manager->persist($fieldOption);
                        // Assign field option to it's field option group
                        $fieldOptionGroup->addFieldOption($fieldOption);
                    }
                    // Append options for Indicator groups of alteast secondary school and University education.

                }
                // Assign created field option groups to groupset by compulsory and hasHistory
                // @Note: names are hard-coded same name as in addDummyFieldGroupsets
                if($humanResourceField['compulsory'] == true) {
                    $compulsoryFieldOptionGroupset = $manager->merge($this->getReference( strtolower(str_replace(' ','','Compulsory Options')).'-fieldoptiongroupset' ));
                    $compulsoryFieldOptionGroupset->addFieldOptionGroup($fieldOptionGroup);
                }
                if($humanResourceField['history'] == true) {
                    $hasHistoryGroupsetByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','','History Options')).'-fieldoptiongroupset' ));
                    $hasHistoryGroupsetByReference->addFieldOptionGroup($fieldOptionGroup);
                }

                $manager->persist($fieldOptionGroup);
            }
            unset($field);
            unset($fieldOptionGroup);
		}
		// Create FieldOptionGroups specific for indicators
        foreach($this->fieldOptionGroups as $fieldOptionGroupKey=>$humanResourceFieldOptionGroup) {
            $fieldOptionGroup = new FieldOptionGroup();
            $fieldOptionGroup->setName($humanResourceFieldOptionGroup['name']);
            $fieldOptionGroup->setField($manager->merge($this->getReference(strtolower($humanResourceFieldOptionGroup['field']))));
            $fieldOptionGroup->setDescription($humanResourceFieldOptionGroup['description']);
            $fieldOptionGroupReference = strtolower(str_replace(' ','',$humanResourceFieldOptionGroup['name'])).'-fieldoptiongroup';
            //Add option members
            foreach($humanResourceFieldOptionGroup['options'] as $dummyFieldOption) {
                $fieldOptionReference = $dummyFieldOption;
                $fieldOptionByReference = $manager->merge($this->getReference( $fieldOptionReference ));
                $fieldOptionGroup->addFieldOption($fieldOptionByReference);
            }
            $this->addReference($fieldOptionGroupReference, $fieldOptionGroup);
            $manager->persist($fieldOptionGroup);
            unset($fieldOptionGroup);
        }

        // Populate dummy field Groups
        foreach($this->fieldGroups as $fieldGroupKey=>$humanResourceFieldGroup) {
            $fieldGroup = new FieldGroup();
            $fieldGroup->setName($humanResourceFieldGroup['name']);
            $fieldGroup->setDescription($humanResourceFieldGroup['description']);
            $fieldGroupReference = strtolower(str_replace(' ','',$humanResourceFieldGroup['name'])).'-fieldgroup';
            $this->addReference($fieldGroupReference, $fieldGroup);
            // Add Unique fields in UniqueField Group
            if($humanResourceFieldGroup['name'] == 'Unique Fields' ) {
                // Parse through fields marked unique
                foreach($this->fields as $fieldKey=>$humanResourceField) {
                    if($humanResourceField['isUnique'] == true) {
                        $fieldReference = strtolower(str_replace(' ','',$humanResourceField['name'])).'-field';
                        $fieldGroup->addField($manager->merge($this->getReference($fieldReference)));
                    }
                }
            }
            //Add compulsory Fields in Compulsory Fields Group
            if($humanResourceFieldGroup['name'] == 'Compulsory Fields' ) {
                // Parse through fields marked unique
                foreach($this->fields as $fieldKey=>$humanResourceField) {
                    if($humanResourceField['compulsory'] == true) {
                        $fieldReference = strtolower(str_replace(' ','',$humanResourceField['name'])).'-field';
                        $fieldGroup->addField($manager->merge($this->getReference($fieldReference)));
                    }
                }
            }
            //Add compulsory Fields in Compulsory Fields Group
            if($humanResourceFieldGroup['name'] == 'Combo Fields' ) {
                // Parse through fields marked unique
                foreach($this->fields as $fieldKey=>$humanResourceField) {
                    if($humanResourceField['inputType'] == 'Select') {
                        $fieldReference = strtolower(str_replace(' ','',$humanResourceField['name'])).'-field';
                        $fieldGroup->addField($manager->merge($this->getReference($fieldReference)));
                    }
                }
            }

            $manager->persist($fieldGroup);
            unset($fieldGroup);
        }
		$manager->flush();
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
        // LoadDataType preceeds
		return 4;
        // LoadFormData follows
	}

}
