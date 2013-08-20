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
                'description'=>"Employee's firstname (Compulsory)",
                'history'=>false),
            1=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'Middlename',
                'caption'=>'Middle name',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Employee's middlename (Optional)",
                'history'=>false),
            2=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'Surname',
                'caption'=>'Surname',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Surname/Lastname(Compulsory)",
                'history'=>true),
            3=>Array(
                'dataType'=>'Date',
                'inputType'=>'Date',
                'name'=>'Birthdate',
                'caption'=>'Date of Birth',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Date of Birth(Compulsory)",
                'history'=>false),
            4=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Sex',
                'caption'=>'Sex',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Sex(Compulsory)",
                'history'=>false),
            5=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'MaritalStatus',
                'caption'=>'Marital Status',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Marital Status(Compulsory)",
                'history'=>true),
            6=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Nationality',
                'caption'=>'Nationality',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Nationality(Compulsory)",
                'history'=>true),
            7=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Religion',
                'caption'=>'Religion',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Religion(Compulsory)",
                'history'=>true),
            8=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'BasicEducationLevel',
                'caption'=>'Basic Education Level',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Basic Education Level(Compulsory)",
                'history'=>true),
            9=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'ProfessionEducationLevel',
                'caption'=>'Profession Education Level',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Profession Education Level(Compulsory)",
                'history'=>true),
            10=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'NumberofChildrenDependants',
                'caption'=>'Number of Children/Dependants',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Number of Children/Dependants(Optional)",
                'history'=>true),
            11=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'DistrictofDomicile',
                'caption'=>'District of Domicile',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Employee's District of Domicile(Optional)",
                'history'=>false),
            12=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'CheckNumber',
                'caption'=>'Check Number',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Check Number(Compulsory)",
                'history'=>true),
            13=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'EmployersFileNumber',
                'caption'=>'Employer`s File Number',
                'compulsory'=>true,
                'isUnique'=>true,
                'description'=>"Employee's Employer`s File Number(Compulsory)",
                'history'=>false),
            14=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'RegistrationNumber',
                'caption'=>'Registration Number',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Employee's Registration Number(Optional)",
                'history'=>false),
            15=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'TermsofEmployment',
                'caption'=>'Terms of Employment',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Terms of Employment(Compulsory)",
                'history'=>true),
            16=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Profession',
                'caption'=>'Profession',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Profession(Compulsory)",
                'history'=>true),
            17=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'PresentDesignation',
                'caption'=>'Present Designation',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Present Designation(Compulsory)",
                'history'=>true),
            18=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'SuperlativeSubstantivePosition',
                'caption'=>'Superlative Substantive Position',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Superlative Substantive Position(Compulsory)",
                'history'=>true),
            19=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Department',
                'caption'=>'Department',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Department(Compulsory)",
                'history'=>true),
            20=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'SalaryScale',
                'caption'=>'Salary Scale',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Salary Scale(Compulsory)",
                'history'=>true),
            21=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'MonthlyBasicSalary',
                'caption'=>'Monthly Basic Salary',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Employee's Monthly Basic Salary(Optional)",
                'history'=>true),
            22=>Array(
                'dataType'=>'String',
                'inputType'=>'Date',
                'name'=>'DateofFirstAppointment',
                'caption'=>'Date of First Appointment',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Date of First Appointment(Compulsory)",
                'history'=>false),
            23=>Array(
                'dataType'=>'String',
                'inputType'=>'Date',
                'name'=>'DateofConfirmation',
                'caption'=>'Date of Confirmation',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Employee's Date of Confirmation(Optional)",
                'history'=>false),
            24=>Array(
                'dataType'=>'String',
                'inputType'=>'Date',
                'name'=>'DateofLastPromotion',
                'caption'=>'Date of Last Promotion',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Employee's Date of Last Promotion(Optional)",
                'history'=>true),
            25=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'Employer',
                'caption'=>'Employer',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Employer(Compulsory)",
                'history'=>true),
            26=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'EmploymentStatus',
                'caption'=>'Employment Status',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Employment Status(Compulsory)",
                'history'=>true),
            27=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'RegisteredDisability',
                'caption'=>'Registered Disability',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Employee's Legal Registered Disability(Optional)",
                'history'=>false),
            28=>Array(
                'dataType'=>'String',
                'inputType'=>'TextArea',
                'name'=>'ContactsofEmployee',
                'caption'=>'Contacts of Employee',
                'compulsory'=>true,
                'isUnique'=>false,
                'description'=>"Employee's Contacts of Employee(Compulsory)",
                'history'=>false),
            29=>Array(
                'dataType'=>'String',
                'inputType'=>'Text',
                'name'=>'NextofKin',
                'caption'=>'Next of Kin',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Employee's Next of Kin(Optional)",
                'history'=>true),
            30=>Array(
                'dataType'=>'String',
                'inputType'=>'Select',
                'name'=>'RelationshiptoNextofKin',
                'caption'=>'Relationship to Next of Kin',
                'compulsory'=>false,
                'isUnique'=>false,
                'description'=>"Employee's Relationship to Next of Kin(Optional)",
                'history'=>true),
            31=>Array(
                'dataType'=>'String',
                'inputType'=>'TextArea',
                'name'=>'ContactsofNextofKin',
                'caption'=>'Contacts of Next of Kin',
                'compulsory'=>false,
                'isUnique'=>false,
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
                'sort' => 1),
            1=>Array(
                'value'=>'Female',
                'description'=>'Employee`s Gender',
                'field'=>  'Sex-field',
                'sort' => 2),
            // Marital status options
            2=>Array(
                'value'=>'Single',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'sort' => 1),
            3=>Array(
                'value'=>'Married',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'sort' => 2),
            4=>Array(
                'value'=>'Separated',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'sort' => 3),
            5=>Array(
                'value'=>'Divorced',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'sort' => 4),
            6=>Array(
                'value'=>'Widow',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'sort' => 5),
            7=>Array(
                'value'=>'Widower',
                'description'=>'Employee`s Marital status',
                'field'=>  'MaritalStatus-field',
                'sort' => 6),
            // Nationality options
            8=>Array(
                'value'=>'Tanzanian',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'sort'=> 1),
            9=>Array(
                'value'=>'Kenyan',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'sort'=> 2),
            10=>Array(
                'value'=>'Ugandan',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'sort'=> 3),
            11=>Array(
                'value'=>'Cuban',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'sort'=> 4),
            12=>Array(
                'value'=>'Somalian',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'sort'=> 5),
            12=>Array(
                'value'=>'Afghanistan',
                'description'=>'Employee`s Nationality',
                'field'=>  'Nationality-field',
                'sort'=> 6),
            // Religion options
            10=>Array(
                'value'=>'Atheist',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'sort'=> 1),
            11=>Array(
                'value'=>'Buddha',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'sort'=> 2),
            12=>Array(
                'value'=>'Christian',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'sort'=> 3),
            13=>Array(
                'value'=>'Hindu',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'sort'=> 4),
            14=>Array(
                'value'=>'Islam',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'sort'=> 5),
            15=>Array(
                'value'=>'Judaism',
                'description'=>'Employee`s Religion',
                'field'=>  'Religion-field',
                'sort'=> 6),
            // Basic education level options
            16=>Array(
                'value'=>'Ordinary Secondary Education',
                'description'=>'Employee`s Basic Education Level',
                'field'=>  'BasicEducationLevel-field',
                'sort'=> 1),
            17=>Array(
                'value'=>'Advanced Secondary Education',
                'description'=>'Employee`s Basic Education Level',
                'field'=>  'BasicEducationLevel-field',
                'sort'=> 2),
            18=>Array(
                'value'=>'Primary Education',
                'description'=>'Employee`s Basic Education Level',
                'field'=>  'BasicEducationLevel-field',
                'sort'=> 3),
            // Profession education level options
            19=>Array(
                'value'=>'None',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'sort'=> 1),
            20=>Array(
                'value'=>'Certificate',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'sort'=> 2),
            21=>Array(
                'value'=>'Advanced Diploma',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'sort'=> 3),
            22=>Array(
                'value'=>'Postgraduate Diploma',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'sort'=> 4),
            23=>Array(
                'value'=>'Bachelor Degree',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'sort'=> 5),
            24=>Array(
                'value'=>'Masters Degree',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'sort'=> 6),
            25=>Array(
                'value'=>'PhD',
                'description'=>'Employee`s Profession Education Level',
                'field'=>  'ProfessionEducationLevel-field',
                'sort'=> 7),
            // Terms of employement options
            26=>Array(
                'value'=>'Permanent and Pensionable',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'sort'=> 1),
            27=>Array(
                'value'=>'Contract',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'sort'=> 2),
            28=>Array(
                'value'=>'Operational Service',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'sort'=> 3),
            29=>Array(
                'value'=>'Volunteer',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'sort'=> 4),
            30=>Array(
                'value'=>'Temporarily',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'sort'=> 5),
            31=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Terms of Employment',
                'field'=>  'TermsofEmployment-field',
                'sort'=> 6),
            // Profession options
            32=>Array(
                'value'=>'Medical Doctor',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'sort'=> 1),
            33=>Array(
                'value'=>'Pharmacist',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'sort'=> 2),
            34=>Array(
                'value'=>'Environmental Health Officer',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'sort'=> 3),
            35=>Array(
                'value'=>'Assistant Medical Officer',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'sort'=> 4),
            36=>Array(
                'value'=>'Nursing Officer',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'sort'=> 5),
            37=>Array(
                'value'=>'Dental Technologist',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'sort'=> 6),
            38=>Array(
                'value'=>'Clinical Assistant',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'sort'=> 7),
            39=>Array(
                'value'=>'Driver',
                'description'=>'Employee`s Profession',
                'field'=>  'Profession-field',
                'sort'=> 8),
            //Present Designation options
            40=>Array(
                'value'=>'Medical Doctor I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 1),
            41=>Array(
                'value'=>'Medical Doctor II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 2),
            42=>Array(
                'value'=>'Principal Medical Doctor I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 3),
            43=>Array(
                'value'=>'Principal Medical Doctor II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 4),
            44=>Array(
                'value'=>'Senior Medical Doctor',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 5),
            45=>Array(
                'value'=>'Nursing Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 1),
            46=>Array(
                'value'=>'Nursing Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 2),
            47=>Array(
                'value'=>'Principal Nursing Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 3),
            48=>Array(
                'value'=>'Principal Nursing Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 4),
            49=>Array(
                'value'=>'Senior Nursing Officer',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 5),
            50=>Array(
                'value'=>'Assistant Medical Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 1,
            51=>Array(
                'value'=>'Assistant Medical Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field'),
                'sort' => 2),
            52=>Array(
                'value'=>'Principal Assistant Medical Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 3),
            53=>Array(
                'value'=>'Principal Assistant Medical Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 4),
            54=>Array(
                'value'=>'Senior Assistant Medical Officer',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 5),
            55=>Array(
                'value'=>'Driver I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 1),
            56=>Array(
                'value'=>'Driver II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 2),
            57=>Array(
                'value'=>'Driver III',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 3),
            58=>Array(
                'value'=>'Pharmacist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 1),
            59=>Array(
                'value'=>'Pharmacist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 2),
            60=>Array(
                'value'=>'Principal Pharmacist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 3),
            61=>Array(
                'value'=>'Principal Pharmacist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 4),
            62=>Array(
                'value'=>'Senior Pharmacist',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 5),
            63=>Array(
                'value'=>'Dental Technologist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 1),
            64=>Array(
                'value'=>'Dental Technologist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 2),
            65=>Array(
                'value'=>'Principal Dental Technologist I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 3),
            66=>Array(
                'value'=>'Principal Dental Technologist II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 4),
            67=>Array(
                'value'=>'Senior Dental Technologist',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 5),
            68=>Array(
                'value'=>'Environmental Health Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 1),
            69=>Array(
                'value'=>'Environmental Health Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 2),
            70=>Array(
                'value'=>'Principal Environmental Health Officer I',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 3),
            71=>Array(
                'value'=>'Principal Environmental Health Officer II',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 4),
            72=>Array(
                'value'=>'Senior Environmental Health Officer',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 5),
            73=>Array(
                'value'=>'Clinical Assistant',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 1),
            74=>Array(
                'value'=>'Principal Clinical Assistant',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 2),
            75=>Array(
                'value'=>'Senior Clinical Assistant',
                'description'=>'Employee`s Present Designation',
                'field'=>  'PresentDesignation-field',
                'sort' => 3),
            // Superlative Substantive Position options
            76=>Array(
                'value'=>'Chief Medical Officer',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 1),
            77=>Array(
                'value'=>'Chief Accountant',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 2),
            78=>Array(
                'value'=>'Chief Nursing Officer',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 3),
            79=>Array(
                'value'=>'Director General',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 4),
            80=>Array(
                'value'=>'Programme Manager',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 5),
            81=>Array(
                'value'=>'Pharmaceutical Advisor',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 7),
            82=>Array(
                'value'=>'Director of Human Resources',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 8,
            83=>Array(
                'value'=>'Director, Medicine and Costmetics',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field'),
                'sort'=> 9),
            84=>Array(
                'value'=>'Director',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 10),
            85=>Array(
                'value'=>'Registrar',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 11),
            86=>Array(
                'value'=>'Deputy Registrar',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 12),
            87=>Array(
                'value'=>'Assistant Director',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 13),
            88=>Array(
                'value'=>'District Executive Director',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 14),
            89=>Array(
                'value'=>'Regional Medical Officer',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 15),
            90=>Array(
                'value'=>'District Medical Officer',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 16),
            91=>Array(
                'value'=>'Regional and District Coordinator',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 17),
            92=>Array(
                'value'=>'Programme Coordinator',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 18),
            93=>Array(
                'value'=>'Hospital Management Team',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 19),
            94=>Array(
                'value'=>'RHMT Co-Opted Member',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 20),
            95=>Array(
                'value'=>'RHMT Member',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 21),
            96=>Array(
                'value'=>'CHMT Co-Opted Member',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 22),
            97=>Array(
                'value'=>'CHMT Member',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 23),
            98=>Array(
                'value'=>'Head of Department',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 24),
            99=>Array(
                'value'=>'Head of Facility',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 25),
            100=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 26),
            101=>Array(
                'value'=>'None',
                'description'=>'Employee`s Superlative Substantive Position',
                'field'=>  'SuperlativeSubstantivePosition-field',
                'sort'=> 27),
            // Department options
            102=>Array(
                'value'=>'Curative Services',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 1,
            103=>Array(
                'value'=>'Preventive Service',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field'),
                'sort'=> 2),
            104=>Array(
                'value'=>'Administration',
                'description'=>'Employee`s Departmentn',
                'field'=>  'Department-field',
                'sort'=> 3),
            105=>Array(
                'value'=>'OutPatient Department',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 4),
            106=>Array(
                'value'=>'Pharmacy',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 5),
            107=>Array(
                'value'=>'Obsy and Gynae',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 6),
            108=>Array(
                'value'=>'Psychiatric',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 7),
            109=>Array(
                'value'=>'Optometry',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 8),
            110=>Array(
                'value'=>'Mortuary',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 9),
            111=>Array(
                'value'=>'Pediatric',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 10),
            112=>Array(
                'value'=>'X-Ray Department',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 11),
            113=>Array(
                'value'=>'Theatre',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 12),
            114=>Array(
                'value'=>'Ophtamology',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 13),
            115=>Array(
                'value'=>'Tuberculosis',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 14),
            116=>Array(
                'value'=>'Dermatology',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 15),
            117=>Array(
                'value'=>'Dental',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 16),
            118=>Array(
                'value'=>'Laboratory',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 17),
            119=>Array(
                'value'=>'Orthopaedics',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 18),
            120=>Array(
                'value'=>'Pathology',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 19),
            121=>Array(
                'value'=>'Surgical Department',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 20),
            122=>Array(
                'value'=>'Transport',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 21),
            123=>Array(
                'value'=>'Security',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 22),
            124=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Department',
                'field'=>  'Department-field',
                'sort'=> 23),
            // Salary scale options
            125=>Array(
                'value'=>'TGHS. A',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 1),
            126=>Array(
                'value'=>'TGHS. B',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 2),
            127=>Array(
                'value'=>'TGHS. C',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 3),
            128=>Array(
                'value'=>'TGHS. D',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 4),
            129=>Array(
                'value'=>'TGHS. E',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 5),
            130=>Array(
                'value'=>'TGHS. F',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 6),
            131=>Array(
                'value'=>'TGHS. G',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 7),
            132=>Array(
                'value'=>'TGHS. H',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 8),
            133=>Array(
                'value'=>'TGHS. I',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 9),
            134=>Array(
                'value'=>'TGHS. J',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 10),
            135=>Array(
                'value'=>'TGHS. K',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 11),
            136=>Array(
                'value'=>'TGHS. L',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 12),
            137=>Array(
                'value'=>'TGHOS. A',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 13),
            138=>Array(
                'value'=>'TGHOS. B',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 14),
            139=>Array(
                'value'=>'TGHOS. C',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 15),
            140=>Array(
                'value'=>'TGHOS. D',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 16),
            141=>Array(
                'value'=>'TGS. A',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 17),
            142=>Array(
                'value'=>'TGS. B',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 18),
            143=>Array(
                'value'=>'TGS. C',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 19),
            144=>Array(
                'value'=>'TGS. D',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 20),
            145=>Array(
                'value'=>'TGS. E',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 21),
            146=>Array(
                'value'=>'TGS. F',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 22),
            147=>Array(
                'value'=>'TGS. G',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 23),
            148=>Array(
                'value'=>'TGS. H',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 24),
            149=>Array(
                'value'=>'TGS. I',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 25),
            148=>Array(
                'value'=>'TGS. K',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 26),
            149=>Array(
                'value'=>'TGS. L',
                'description'=>'Employee`s Salary Scale',
                'field'=>  'SalaryScale-field',
                'sort'=> 27),
            // Employer options
            150=>Array(
                'value'=>'Ministry of Health and Social Welfare',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 1),
            151=>Array(
                'value'=>'Prime Minister Office - RALG',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 2),
            152=>Array(
                'value'=>'President Office- Public Service Management',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 3),
            153=>Array(
                'value'=>'Regional Administrative Secretary',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 4),
            154=>Array(
                'value'=>'Municipal Director',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 5),
            155=>Array(
                'value'=>'Town Director',
                'description'=>'Employer`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 6),
            156=>Array(
                'value'=>'City Council Director',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 7),
            157=>Array(
                'value'=>'District Executive Director',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 8),
            158=>Array(
                'value'=>'Good Samaritan Foundation',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 9),
            159=>Array(
                'value'=>'Faith Based Organisation',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 10),
            158=>Array(
                'value'=>'Parastatal',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 11),
            159=>Array(
                'value'=>'Private',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 12),
            160=>Array(
                'value'=>'Army',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 13),
            161=>Array(
                'value'=>'Tanzania Food and Drug Authority',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 14),
            162=>Array(
                'value'=>'Tanzania Food and Nutrition Center',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 15),
            163=>Array(
                'value'=>'Muhimbili Orthopaedic Institute',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 16),
            164=>Array(
                'value'=>'Muhimbili National Hospital',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 17),
            165=>Array(
                'value'=>'Ocean Road Cancer Institute',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 18),
            166=>Array(
                'value'=>'Pharmacy Council',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 19),
            167=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Employer',
                'field'=>  'Employer-field',
                'sort'=> 20),
            //Employment Status options
            168=>Array(
                'value'=>'On Duty',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 1),
            169=>Array(
                'value'=>'Retired',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 2),
            170=>Array(
                'value'=>'Transfered',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 3),
            171=>Array(
                'value'=>'Off Duty',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 4),
            172=>Array(
                'value'=>'On Leave Without Employment Status',
                'description'=>'Employee`s Employer',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 5),
            173=>Array(
                'value'=>'Deceased',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 6),
            174=>Array(
                'value'=>'On Secondment',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 7),
            175=>Array(
                'value'=>'On Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 8),
            176=>Array(
                'value'=>'On Study Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 9),
            177=>Array(
                'value'=>'On Maternity Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 10),
            178=>Array(
                'value'=>'On Annual Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 11),
            179=>Array(
                'value'=>'On Sick Leave',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 12),
            180=>Array(
                'value'=>'Resigned',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 13),
            181=>Array(
                'value'=>'Abscondent',
                'description'=>'Employee`s Employment Status',
                'field'=>  'EmploymentStatus-field',
                'sort'=> 14),
            //Registered disability options
            182=>Array(
                'value'=>'Physical Disability',
                'description'=>'Employee`s Registered Disability',
                'field'=>  'RegisteredDisability-field',
                'sort'=> 1),
            183=>Array(
                'value'=>'Visual Impaired',
                'description'=>'Employee`s Registered Disability',
                'field'=>  'RegisteredDisability-field',
                'sort'=> 2),
            184=>Array(
                'value'=>'None',
                'description'=>'Employee`s Registered Disability',
                'field'=>  'RegisteredDisability-field',
                'sort'=> 3),
            185=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Registered Disability',
                'field'=>  'RegisteredDisability-field',
                'sort'=> 4),
            // Relationship to Next of Kin options
            186=>Array(
                'value'=>'Superior of Congregation',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'sort'=> 1),
            187=>Array(
                'value'=>'Child',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'sort'=> 2),
            188=>Array(
                'value'=>'Sibling',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'sort'=> 3),
            189=>Array(
                'value'=>'Parent',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'sort'=> 4),
            190=>Array(
                'value'=>'Spouse',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'sort'=> 5),
            191=>Array(
                'value'=>'Other',
                'description'=>'Employee`s Relationship to Next of Kin',
                'field'=>  'RelationshiptoNextofKin-field',
                'sort'=> 6),


        );
        return $this->fieldOptions;
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
                'description'=>"Fields with combo options")
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
        $this->addDummyFieldGroups();
        $this->addDummyFieldGroupsets();

        // Create FieldOptionGroupset for later OptionGroups assignment
        foreach($this->fieldOptionGroupsets as $fieldOptionGroupsetKey=>$humanResourceFieldOptionGroupsets) {
            $fieldOptionGroupset = new FieldOptionGroupset();
            $fieldOptionGroupset->setName($humanResourceFieldOptionGroupsets['name']);
            $fieldOptionGroupset->setDescription($humanResourceFieldOptionGroupsets['description']);
            $fieldReference = strtolower(str_replace(' ','',$humanResourceFieldOptionGroupsets['name'])).'-fieldoptiongroupset';
            $this->addReference($fieldReference, $fieldOptionGroupset);
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
			$field->setCompulsory($humanResourceField['compulsory']);
            $field->setIsUnique($humanResourceField['isUnique']);
            $fieldReference = strtolower(str_replace(' ','',$humanResourceField['name'])).'-field';
			$this->addReference($fieldReference, $field);
            $manager->persist($field);
            // Append  dummy options for input type of select.
            if($humanResourceField['inputType'] == 'Select') {

                //Create Field Option Group by Field
                $fieldOptionGroup = new FieldOptionGroup();
                $fieldOptionGroup->setName($humanResourceField['name']);
                $fieldOptionGroup->setDescription($humanResourceField['description']);
                $fieldOptionGroupReference = strtolower(str_replace(' ','',$humanResourceField['name'])).'-fieldoptiongroup';
                $this->addReference($fieldOptionGroupReference, $fieldOptionGroup);

                // Assign field options to their fields & field  option group
                foreach($this->fieldOptions as $fieldOptionKey=> $humanResourceFieldOptions) {
                    if(str_replace('-field','',$humanResourceFieldOptions['field']) == str_replace(' ','',$humanResourceField['name']) ) {
                        $fieldOption = new FieldOption();
                        $fieldOption->setField( $manager->merge($this->getReference($fieldReference)) );
                        $fieldOption->setSort($humanResourceFieldOptions['sort']);
                        $fieldOption->setValue($humanResourceFieldOptions['value']);
                        $fieldOption->setDescription($humanResourceFieldOptions['description']);
                        $fieldOptionReference = strtolower(str_replace(' ','',$humanResourceFieldOptions['value'])).str_replace('-field','',$humanResourceFieldOptions['field']).'-fieldoption';
                        $this->addReference($fieldOptionReference, $fieldOption);
                        $manager->persist($fieldOption);
                        // Assign field option to it's field option group
                        $fieldOptionGroup->addFieldOption($fieldOption);
                    }
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
        }
		$manager->flush();
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
		return 4;
	}

}
