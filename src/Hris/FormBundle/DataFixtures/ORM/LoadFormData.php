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

use Hris\FormBundle\Entity\Form;
use Hris\FormBundle\Entity\FormFieldMember;
use Hris\FormBundle\DataFixtures\ORM\LoadFieldData;

class LoadFormData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
    private $forms;

    /**
     * Returns array of form fixtures.
     *
     * @return mixed
     */
    public function getForms()
    {
        return $this->forms;
    }

    /**
     * Returns array of dummy forms
     * @return array
     */
    public function addDummyForms()
    {
        // Load Public Data
        $this->forms = Array(
            0=>Array(
                'name'=>'Public Employee Form',
                'title'=>'Public Employee Data Entry Form',
                'hypertext'=>'
                <table class="dataentry" border="1">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <p><span>Public Employee Data Entry Form</span></p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><em>1.</em></td>
                                <td><span>First Name</span></td>
                                <td class="whitebg"><input type="text" name="FirstName" id="FirstName"/></td>
                            </tr>
                            <tr>
                                <td><span>2.</span></td>
                                <td><span>Middle Name</span></td>
                                <td><input type="text" name="MiddleName" id="MiddleName"/></td>
                            </tr>
                            <tr>
                                <td><span>3.</span></td>
                                <td><span>Surname</span></td>
                                <td class="whitebg"><input type="text" name="Surname" id="Surname"/></td>
                            </tr>
                            <tr>
                                <td><span>4.</span></td>
                                <td><span>Date of Birth</span></td>
                                <td><input type="date" name="DateofBirth" id="DateofBirth" /></td>
                            </tr>
                            <tr>
                                <td><span>5.</span></td>
                                <td><span>Sex</span></td>
                                <td class="whitebg"><select name="Sex" id="Sex" onload="loadFieldOptions(\'Sex\')" onchange="changeRelatedFieldOptions(\'Sex\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>6.</span></td>
                                <td><span>Marital Status</span></td>
                                <td><select name="MaritalStatus" id="MaritalStatus" onload="loadFieldOptions(\'MaritalStatus\')" onchange="changeRelatedFieldOptions(\'MaritalStatus\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>7.</span></td>
                                <td><span>Nationality</span></td>
                                <td class="whitebg"><select name="Nationality" id="Nationality" onload="loadFieldOptions(\'Nationality\')" onchange="changeRelatedFieldOptions(\'Nationality\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>8.</span></td>
                                <td><span>Religion</span></td>
                                <td><select name="Religion" id="Religion" onload="loadFieldOptions(\'Religion\')" onchange="changeRelatedFieldOptions(\'Religion\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>9.</span></td>
                                <td><span>Basic Education Level</span></td>
                                <td class="whitebg"><select name="BasicEducationLevel" id="BasicEducationLevel" onload="loadFieldOptions(\'BasicEducationLevel\')" onchange="changeRelatedFieldOptions(\'BasicEducationLevel\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>10.</span></td>
                                <td><span>Profession Education Level</span></td>
                                <td><select name="ProfessionEducationLevel" id="ProfessionEducationLevel" onload="loadFieldOptions(\'ProfessionEducationLevel\')" onchange="changeRelatedFieldOptions(\'ProfessionEducationLevel\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>11.</span></td>
                                <td><span>Number of Children/Dependants</span></td>
                                <td class="whitebg"><input type="text" name="NumberofChildrenDependants" id="NumberofChildrenDependants"/></td>
                            </tr>
                            <tr>
                                <td><span>12.</span></td>
                                <td><span>District of Domicile</span></td>
                                <td><input type="text" name="DistrictofDomicile" id="DistrictofDomicile"/></td>
                            </tr>
                            <tr>
                                <td><span>13.</span></td>
                                <td><span>Check Number</span></td>
                                <td class="whitebg"><input type="text" name="CheckNumber" id="CheckNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>14.</span></td>
                                <td><span>Employer`s File Number</span></td>
                                <td><input type="text" name="EmployersFileNumber" id="EmployersFileNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>15.</span></td>
                                <td><span>Registration Number</span></td>
                                <td class="whitebg"><input type="text" name="RegistrationNumber" id="RegistrationNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>16.</span></td>
                                <td><span>Terms of Employment</span></td>
                                <td><select name="TermsofEmployment" id="TermsofEmployment" onload="loadFieldOptions(\'TermsofEmployment\')" onchange="changeRelatedFieldOptions(\'TermsofEmployment\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>17.</span></td>
                                <td><span>Profession</span></td>
                                <td class="whitebg"><select name="Profession" id="Profession" onload="loadFieldOptions(\'Profession\')" onchange="changeRelatedFieldOptions(\'Profession\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>18.</span></td>
                                <td><span>Present Designation</span></td>
                                <td><select name="PresentDesignation" id="PresentDesignation" onload="loadFieldOptions(\'PresentDesignation\')" onchange="changeRelatedFieldOptions(\'PresentDesignation\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>19.</span></td>
                                <td><span>Superlative Substantive Position</span></td>
                                <td class="whitebg"><select name="SuperlativeSubstantivePosition" id="SuperlativeSubstantivePosition" onload="loadFieldOptions(\'SuperlativeSubstantivePosition\')" onchange="changeRelatedFieldOptions(\'SuperlativeSubstantivePosition\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>20.</span></td>
                                <td><span>Department</span></td>
                                <td><select name="Department" id="Department" onload="loadFieldOptions(\'Department\')" onchange="changeRelatedFieldOptions(\'Department\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>21.</span></td>
                                <td><span>Salary Scale</span></td>
                                <td class="whitebg"><select name="SalaryScale" id="SalaryScale" onload="loadFieldOptions(\'SalaryScale\')" onchange="changeRelatedFieldOptions(\'SalaryScale\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>22.</span></td>
                                <td><span>Monthly Basic Salary</span></td>
                                <td><input type="text" name="MonthlyBasicSalary" id="MonthlyBasicSalary"/></td>
                            </tr>
                            <tr>
                                <td><span>23.</span></td>
                                <td><span>Date of First Appointment</span></td>
                                <td class="whitebg"><input type="date" name="DateofFirstAppointment" id="DateofFirstAppointment" /></td>
                            </tr>
                            <tr>
                                <td><span>24.</span></td>
                                <td><span>Date of Confirmation</span></td>
                                <td><input type="date" name="DateofConfirmation" id="DateofConfirmation" /></td>
                            </tr>
                            <tr>
                                <td><span>25.</span></td>
                                <td><span>Date of Last Promotion</span></td>
                                <td class="whitebg"><input type="date" name="DateofLastPromotion" id="DateofLastPromotion" /></td>
                            </tr>
                            <tr>
                                <td><span>26.</span></td>
                                <td><span>Employer</span></td>
                                <td><select name="Employer" id="Employer" onload="loadFieldOptions(\'Employer\')" onchange="changeRelatedFieldOptions(\'Employer\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>27.</span></td>
                                <td><span>Employment Status</span></td>
                                <td class="whitebg"><select name="EmploymentStatus" id="EmploymentStatus" onload="loadFieldOptions(\'EmploymentStatus\')" onchange="changeRelatedFieldOptions(\'EmploymentStatus\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>28.</span></td>
                                <td><span>Registered Disability</span></td>
                                <td><select name="RegisteredDisability" id="RegisteredDisability" onload="loadFieldOptions(\'RegisteredDisability\')" onchange="changeRelatedFieldOptions(\'RegisteredDisability\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>29.</span></td>
                                <td><span>Contacts of Employee</span></td>
                                <td class="whitebg"><textarea name="ContactsofEmployee" id="ContactsofEmployee"></textarea></td>
                            </tr>
                            <tr>
                                <td><span>30.</span></td>
                                <td><span>Next of Kin</span></td>
                                <td><input type="text" name="NextofKin" id="NextofKin"/></td>
                            </tr>
                            <tr>
                                <td><span>31.</span></td>
                                <td><span>Relationship to Next of Kin</span></td>
                                <td class="whitebg"><select name="RelationshiptoNextofKin" id="RelationshiptoNextofKin" onload="loadFieldOptions(\'RelationshiptoNextofKin\')" onchange="changeRelatedFieldOptions(\'RelationshiptoNextofKin\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>32.</span></td>
                                <td><span>Contacts of Next of Kin</span></td>
                                <td><textarea name="ContactsofNextofKin" id="ContactsofNextofKin"></textarea></td>
                            </tr>
                        </tbody>
                </table>
                '),
            1=>Array(
                'name'=>'Private Employee Form',
                'title'=>'Private Employee Data Entry Form',
                'hypertext'=>'
                <table class="dataentry" border="1">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <p><span>Private Employee Data Entry Form</span></p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><em>1.</em></td>
                                <td><span>First Name</span></td>
                                <td class="whitebg"><input type="text" name="FirstName" id="FirstName"/></td>
                            </tr>
                            <tr>
                                <td><span>2.</span></td>
                                <td><span>Middle Name</span></td>
                                <td><input type="text" name="MiddleName" id="MiddleName"/></td>
                            </tr>
                            <tr>
                                <td><span>3.</span></td>
                                <td><span>Surname</span></td>
                                <td class="whitebg"><input type="text" name="Surname" id="Surname"/></td>
                            </tr>
                            <tr>
                                <td><span>4.</span></td>
                                <td><span>Date of Birth</span></td>
                                <td><input type="date" name="DateofBirth" id="DateofBirth" /></td>
                            </tr>
                            <tr>
                                <td><span>5.</span></td>
                                <td><span>Sex</span></td>
                                <td class="whitebg"><select name="Sex" id="Sex" onload="loadFieldOptions(\'Sex\')" onchange="changeRelatedFieldOptions(\'Sex\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>6.</span></td>
                                <td><span>Marital Status</span></td>
                                <td><select name="MaritalStatus" id="MaritalStatus" onload="loadFieldOptions(\'MaritalStatus\')" onchange="changeRelatedFieldOptions(\'MaritalStatus\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>7.</span></td>
                                <td><span>Nationality</span></td>
                                <td class="whitebg"><select name="Nationality" id="Nationality" onload="loadFieldOptions(\'Nationality\')" onchange="changeRelatedFieldOptions(\'Nationality\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>8.</span></td>
                                <td><span>Religion</span></td>
                                <td><select name="Religion" id="Religion" onload="loadFieldOptions(\'Religion\')" onchange="changeRelatedFieldOptions(\'Religion\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>9.</span></td>
                                <td><span>Basic Education Level</span></td>
                                <td class="whitebg"><select name="BasicEducationLevel" id="BasicEducationLevel" onload="loadFieldOptions(\'BasicEducationLevel\')" onchange="changeRelatedFieldOptions(\'BasicEducationLevel\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>10.</span></td>
                                <td><span>Profession Education Level</span></td>
                                <td><select name="ProfessionEducationLevel" id="ProfessionEducationLevel" onload="loadFieldOptions(\'ProfessionEducationLevel\')" onchange="changeRelatedFieldOptions(\'ProfessionEducationLevel\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>11.</span></td>
                                <td><span>Number of Children/Dependants</span></td>
                                <td class="whitebg"><input type="text" name="NumberofChildrenDependants" id="NumberofChildrenDependants"/></td>
                            </tr>
                            <tr>
                                <td><span>12.</span></td>
                                <td><span>District of Domicile</span></td>
                                <td><input type="text" name="DistrictofDomicile" id="DistrictofDomicile"/></td>
                            </tr>
                            <tr>
                                <td><span>13.</span></td>
                                <td><span>Check Number</span></td>
                                <td class="whitebg"><input type="text" name="CheckNumber" id="CheckNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>14.</span></td>
                                <td><span>Employer`s File Number</span></td>
                                <td><input type="text" name="EmployersFileNumber" id="EmployersFileNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>15.</span></td>
                                <td><span>Registration Number</span></td>
                                <td class="whitebg"><input type="text" name="RegistrationNumber" id="RegistrationNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>16.</span></td>
                                <td><span>Terms of Employment</span></td>
                                <td><select name="TermsofEmployment" id="TermsofEmployment" onload="loadFieldOptions(\'TermsofEmployment\')" onchange="changeRelatedFieldOptions(\'TermsofEmployment\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>17.</span></td>
                                <td><span>Profession</span></td>
                                <td class="whitebg"><select name="Profession" id="Profession" onload="loadFieldOptions(\'Profession\')" onchange="changeRelatedFieldOptions(\'Profession\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>18.</span></td>
                                <td><span>Present Designation</span></td>
                                <td><select name="PresentDesignation" id="PresentDesignation" onload="loadFieldOptions(\'PresentDesignation\')" onchange="changeRelatedFieldOptions(\'PresentDesignation\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>19.</span></td>
                                <td><span>Superlative Substantive Position</span></td>
                                <td class="whitebg"><select name="SuperlativeSubstantivePosition" id="SuperlativeSubstantivePosition" onload="loadFieldOptions(\'SuperlativeSubstantivePosition\')" onchange="changeRelatedFieldOptions(\'SuperlativeSubstantivePosition\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>20.</span></td>
                                <td><span>Department</span></td>
                                <td><select name="Department" id="Department" onload="loadFieldOptions(\'Department\')" onchange="changeRelatedFieldOptions(\'Department\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>21.</span></td>
                                <td><span>Salary Scale</span></td>
                                <td class="whitebg"><select name="SalaryScale" id="SalaryScale" onload="loadFieldOptions(\'SalaryScale\')" onchange="changeRelatedFieldOptions(\'SalaryScale\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>22.</span></td>
                                <td><span>Monthly Basic Salary</span></td>
                                <td><input type="text" name="MonthlyBasicSalary" id="MonthlyBasicSalary"/></td>
                            </tr>
                            <tr>
                                <td><span>23.</span></td>
                                <td><span>Date of First Appointment</span></td>
                                <td class="whitebg"><input type="date" name="DateofFirstAppointment" id="DateofFirstAppointment" /></td>
                            </tr>
                            <tr>
                                <td><span>24.</span></td>
                                <td><span>Date of Confirmation</span></td>
                                <td><input type="date" name="DateofConfirmation" id="DateofConfirmation" /></td>
                            </tr>
                            <tr>
                                <td><span>25.</span></td>
                                <td><span>Date of Last Promotion</span></td>
                                <td class="whitebg"><input type="date" name="DateofLastPromotion" id="DateofLastPromotion" /></td>
                            </tr>
                            <tr>
                                <td><span>26.</span></td>
                                <td><span>Employer</span></td>
                                <td><select name="Employer" id="Employer" onload="loadFieldOptions(\'Employer\')" onchange="changeRelatedFieldOptions(\'Employer\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>27.</span></td>
                                <td><span>Employment Status</span></td>
                                <td class="whitebg"><select name="EmploymentStatus" id="EmploymentStatus" onload="loadFieldOptions(\'EmploymentStatus\')" onchange="changeRelatedFieldOptions(\'EmploymentStatus\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>28.</span></td>
                                <td><span>Registered Disability</span></td>
                                <td><select name="RegisteredDisability" id="RegisteredDisability" onload="loadFieldOptions(\'RegisteredDisability\')" onchange="changeRelatedFieldOptions(\'RegisteredDisability\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>29.</span></td>
                                <td><span>Contacts of Employee</span></td>
                                <td class="whitebg"><textarea name="ContactsofEmployee" id="ContactsofEmployee"></textarea></td>
                            </tr>
                            <tr>
                                <td><span>30.</span></td>
                                <td><span>Next of Kin</span></td>
                                <td><input type="text" name="NextofKin" id="NextofKin"/></td>
                            </tr>
                            <tr>
                                <td><span>31.</span></td>
                                <td><span>Relationship to Next of Kin</span></td>
                                <td class="whitebg"><select name="RelationshiptoNextofKin" id="RelationshiptoNextofKin" onload="loadFieldOptions(\'RelationshiptoNextofKin\')" onchange="changeRelatedFieldOptions(\'RelationshiptoNextofKin\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>32.</span></td>
                                <td><span>Contacts of Next of Kin</span></td>
                                <td><textarea name="ContactsofNextofKin" id="ContactsofNextofKin"></textarea></td>
                            </tr>
                        </tbody>
                </table>
                '),
            2=>Array(
                'name'=>'Hospital Employee Form',
                'title'=>'Hospital Employee Data Entry Form',
                'hypertext'=>'
                <table class="dataentry" border="1">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <p><span>Hospital Employee Data Entry Form</span></p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><em>1.</em></td>
                                <td><span>First Name</span></td>
                                <td class="whitebg"><input type="text" name="FirstName" id="FirstName"/></td>
                            </tr>
                            <tr>
                                <td><span>2.</span></td>
                                <td><span>Middle Name</span></td>
                                <td><input type="text" name="MiddleName" id="MiddleName"/></td>
                            </tr>
                            <tr>
                                <td><span>3.</span></td>
                                <td><span>Surname</span></td>
                                <td class="whitebg"><input type="text" name="Surname" id="Surname"/></td>
                            </tr>
                            <tr>
                                <td><span>4.</span></td>
                                <td><span>Date of Birth</span></td>
                                <td><input type="date" name="DateofBirth" id="DateofBirth" /></td>
                            </tr>
                            <tr>
                                <td><span>5.</span></td>
                                <td><span>Sex</span></td>
                                <td class="whitebg"><select name="Sex" id="Sex" onload="loadFieldOptions(\'Sex\')" onchange="changeRelatedFieldOptions(\'Sex\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>6.</span></td>
                                <td><span>Marital Status</span></td>
                                <td><select name="MaritalStatus" id="MaritalStatus" onload="loadFieldOptions(\'MaritalStatus\')" onchange="changeRelatedFieldOptions(\'MaritalStatus\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>7.</span></td>
                                <td><span>Nationality</span></td>
                                <td class="whitebg"><select name="Nationality" id="Nationality" onload="loadFieldOptions(\'Nationality\')" onchange="changeRelatedFieldOptions(\'Nationality\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>8.</span></td>
                                <td><span>Religion</span></td>
                                <td><select name="Religion" id="Religion" onload="loadFieldOptions(\'Religion\')" onchange="changeRelatedFieldOptions(\'Religion\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>9.</span></td>
                                <td><span>Basic Education Level</span></td>
                                <td class="whitebg"><select name="BasicEducationLevel" id="BasicEducationLevel" onload="loadFieldOptions(\'BasicEducationLevel\')" onchange="changeRelatedFieldOptions(\'BasicEducationLevel\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>10.</span></td>
                                <td><span>Profession Education Level</span></td>
                                <td><select name="ProfessionEducationLevel" id="ProfessionEducationLevel" onload="loadFieldOptions(\'ProfessionEducationLevel\')" onchange="changeRelatedFieldOptions(\'ProfessionEducationLevel\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>11.</span></td>
                                <td><span>Number of Children/Dependants</span></td>
                                <td class="whitebg"><input type="text" name="NumberofChildrenDependants" id="NumberofChildrenDependants"/></td>
                            </tr>
                            <tr>
                                <td><span>12.</span></td>
                                <td><span>District of Domicile</span></td>
                                <td><input type="text" name="DistrictofDomicile" id="DistrictofDomicile"/></td>
                            </tr>
                            <tr>
                                <td><span>13.</span></td>
                                <td><span>Check Number</span></td>
                                <td class="whitebg"><input type="text" name="CheckNumber" id="CheckNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>14.</span></td>
                                <td><span>Employer`s File Number</span></td>
                                <td><input type="text" name="EmployersFileNumber" id="EmployersFileNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>15.</span></td>
                                <td><span>Registration Number</span></td>
                                <td class="whitebg"><input type="text" name="RegistrationNumber" id="RegistrationNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>16.</span></td>
                                <td><span>Terms of Employment</span></td>
                                <td><select name="TermsofEmployment" id="TermsofEmployment" onload="loadFieldOptions(\'TermsofEmployment\')" onchange="changeRelatedFieldOptions(\'TermsofEmployment\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>17.</span></td>
                                <td><span>Profession</span></td>
                                <td class="whitebg"><select name="Profession" id="Profession" onload="loadFieldOptions(\'Profession\')" onchange="changeRelatedFieldOptions(\'Profession\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>18.</span></td>
                                <td><span>Present Designation</span></td>
                                <td><select name="PresentDesignation" id="PresentDesignation" onload="loadFieldOptions(\'PresentDesignation\')" onchange="changeRelatedFieldOptions(\'PresentDesignation\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>19.</span></td>
                                <td><span>Superlative Substantive Position</span></td>
                                <td class="whitebg"><select name="SuperlativeSubstantivePosition" id="SuperlativeSubstantivePosition" onload="loadFieldOptions(\'SuperlativeSubstantivePosition\')" onchange="changeRelatedFieldOptions(\'SuperlativeSubstantivePosition\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>20.</span></td>
                                <td><span>Department</span></td>
                                <td><select name="Department" id="Department" onload="loadFieldOptions(\'Department\')" onchange="changeRelatedFieldOptions(\'Department\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>21.</span></td>
                                <td><span>Salary Scale</span></td>
                                <td class="whitebg"><select name="SalaryScale" id="SalaryScale" onload="loadFieldOptions(\'SalaryScale\')" onchange="changeRelatedFieldOptions(\'SalaryScale\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>22.</span></td>
                                <td><span>Monthly Basic Salary</span></td>
                                <td><input type="text" name="MonthlyBasicSalary" id="MonthlyBasicSalary"/></td>
                            </tr>
                            <tr>
                                <td><span>23.</span></td>
                                <td><span>Date of First Appointment</span></td>
                                <td class="whitebg"><input type="date" name="DateofFirstAppointment" id="DateofFirstAppointment" /></td>
                            </tr>
                            <tr>
                                <td><span>24.</span></td>
                                <td><span>Date of Confirmation</span></td>
                                <td><input type="date" name="DateofConfirmation" id="DateofConfirmation" /></td>
                            </tr>
                            <tr>
                                <td><span>25.</span></td>
                                <td><span>Date of Last Promotion</span></td>
                                <td class="whitebg"><input type="date" name="DateofLastPromotion" id="DateofLastPromotion" /></td>
                            </tr>
                            <tr>
                                <td><span>26.</span></td>
                                <td><span>Employer</span></td>
                                <td><select name="Employer" id="Employer" onload="loadFieldOptions(\'Employer\')" onchange="changeRelatedFieldOptions(\'Employer\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>27.</span></td>
                                <td><span>Employment Status</span></td>
                                <td class="whitebg"><select name="EmploymentStatus" id="EmploymentStatus" onload="loadFieldOptions(\'EmploymentStatus\')" onchange="changeRelatedFieldOptions(\'EmploymentStatus\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>28.</span></td>
                                <td><span>Registered Disability</span></td>
                                <td><select name="RegisteredDisability" id="RegisteredDisability" onload="loadFieldOptions(\'RegisteredDisability\')" onchange="changeRelatedFieldOptions(\'RegisteredDisability\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>29.</span></td>
                                <td><span>Contacts of Employee</span></td>
                                <td class="whitebg"><textarea name="ContactsofEmployee" id="ContactsofEmployee"></textarea></td>
                            </tr>
                            <tr>
                                <td><span>30.</span></td>
                                <td><span>Next of Kin</span></td>
                                <td><input type="text" name="NextofKin" id="NextofKin"/></td>
                            </tr>
                            <tr>
                                <td><span>31.</span></td>
                                <td><span>Relationship to Next of Kin</span></td>
                                <td class="whitebg"><select name="RelationshiptoNextofKin" id="RelationshiptoNextofKin" onload="loadFieldOptions(\'RelationshiptoNextofKin\')" onchange="changeRelatedFieldOptions(\'RelationshiptoNextofKin\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>32.</span></td>
                                <td><span>Contacts of Next of Kin</span></td>
                                <td><textarea name="ContactsofNextofKin" id="ContactsofNextofKin"></textarea></td>
                            </tr>
                        </tbody>
                </table>
                '),
            3=>Array(
                'name'=>'Training Institution Employee Form',
                'title'=>'Training Institution Employee Form',
                'hypertext'=>'
                <table class="dataentry" border="1">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <p><span>Training Institution Employee Form</span></p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><em>1.</em></td>
                                <td><span>First Name</span></td>
                                <td class="whitebg"><input type="text" name="FirstName" id="FirstName"/></td>
                            </tr>
                            <tr>
                                <td><span>2.</span></td>
                                <td><span>Middle Name</span></td>
                                <td><input type="text" name="MiddleName" id="MiddleName"/></td>
                            </tr>
                            <tr>
                                <td><span>3.</span></td>
                                <td><span>Surname</span></td>
                                <td class="whitebg"><input type="text" name="Surname" id="Surname"/></td>
                            </tr>
                            <tr>
                                <td><span>4.</span></td>
                                <td><span>Date of Birth</span></td>
                                <td><input type="date" name="DateofBirth" id="DateofBirth" /></td>
                            </tr>
                            <tr>
                                <td><span>5.</span></td>
                                <td><span>Sex</span></td>
                                <td class="whitebg"><select name="Sex" id="Sex" onload="loadFieldOptions(\'Sex\')" onchange="changeRelatedFieldOptions(\'Sex\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>6.</span></td>
                                <td><span>Marital Status</span></td>
                                <td><select name="MaritalStatus" id="MaritalStatus" onload="loadFieldOptions(\'MaritalStatus\')" onchange="changeRelatedFieldOptions(\'MaritalStatus\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>7.</span></td>
                                <td><span>Nationality</span></td>
                                <td class="whitebg"><select name="Nationality" id="Nationality" onload="loadFieldOptions(\'Nationality\')" onchange="changeRelatedFieldOptions(\'Nationality\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>8.</span></td>
                                <td><span>Religion</span></td>
                                <td><select name="Religion" id="Religion" onload="loadFieldOptions(\'Religion\')" onchange="changeRelatedFieldOptions(\'Religion\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>9.</span></td>
                                <td><span>Basic Education Level</span></td>
                                <td class="whitebg"><select name="BasicEducationLevel" id="BasicEducationLevel" onload="loadFieldOptions(\'BasicEducationLevel\')" onchange="changeRelatedFieldOptions(\'BasicEducationLevel\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>10.</span></td>
                                <td><span>Profession Education Level</span></td>
                                <td><select name="ProfessionEducationLevel" id="ProfessionEducationLevel" onload="loadFieldOptions(\'ProfessionEducationLevel\')" onchange="changeRelatedFieldOptions(\'ProfessionEducationLevel\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>11.</span></td>
                                <td><span>Number of Children/Dependants</span></td>
                                <td class="whitebg"><input type="text" name="NumberofChildrenDependants" id="NumberofChildrenDependants"/></td>
                            </tr>
                            <tr>
                                <td><span>12.</span></td>
                                <td><span>District of Domicile</span></td>
                                <td><input type="text" name="DistrictofDomicile" id="DistrictofDomicile"/></td>
                            </tr>
                            <tr>
                                <td><span>13.</span></td>
                                <td><span>Check Number</span></td>
                                <td class="whitebg"><input type="text" name="CheckNumber" id="CheckNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>14.</span></td>
                                <td><span>Employer`s File Number</span></td>
                                <td><input type="text" name="EmployersFileNumber" id="EmployersFileNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>15.</span></td>
                                <td><span>Registration Number</span></td>
                                <td class="whitebg"><input type="text" name="RegistrationNumber" id="RegistrationNumber"/></td>
                            </tr>
                            <tr>
                                <td><span>16.</span></td>
                                <td><span>Terms of Employment</span></td>
                                <td><select name="TermsofEmployment" id="TermsofEmployment" onload="loadFieldOptions(\'TermsofEmployment\')" onchange="changeRelatedFieldOptions(\'TermsofEmployment\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>17.</span></td>
                                <td><span>Profession</span></td>
                                <td class="whitebg"><select name="Profession" id="Profession" onload="loadFieldOptions(\'Profession\')" onchange="changeRelatedFieldOptions(\'Profession\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>18.</span></td>
                                <td><span>Present Designation</span></td>
                                <td><select name="PresentDesignation" id="PresentDesignation" onload="loadFieldOptions(\'PresentDesignation\')" onchange="changeRelatedFieldOptions(\'PresentDesignation\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>19.</span></td>
                                <td><span>Superlative Substantive Position</span></td>
                                <td class="whitebg"><select name="SuperlativeSubstantivePosition" id="SuperlativeSubstantivePosition" onload="loadFieldOptions(\'SuperlativeSubstantivePosition\')" onchange="changeRelatedFieldOptions(\'SuperlativeSubstantivePosition\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>20.</span></td>
                                <td><span>Department</span></td>
                                <td><select name="Department" id="Department" onload="loadFieldOptions(\'Department\')" onchange="changeRelatedFieldOptions(\'Department\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>21.</span></td>
                                <td><span>Salary Scale</span></td>
                                <td class="whitebg"><select name="SalaryScale" id="SalaryScale" onload="loadFieldOptions(\'SalaryScale\')" onchange="changeRelatedFieldOptions(\'SalaryScale\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>22.</span></td>
                                <td><span>Monthly Basic Salary</span></td>
                                <td><input type="text" name="MonthlyBasicSalary" id="MonthlyBasicSalary"/></td>
                            </tr>
                            <tr>
                                <td><span>23.</span></td>
                                <td><span>Date of First Appointment</span></td>
                                <td class="whitebg"><input type="date" name="DateofFirstAppointment" id="DateofFirstAppointment" /></td>
                            </tr>
                            <tr>
                                <td><span>24.</span></td>
                                <td><span>Date of Confirmation</span></td>
                                <td><input type="date" name="DateofConfirmation" id="DateofConfirmation" /></td>
                            </tr>
                            <tr>
                                <td><span>25.</span></td>
                                <td><span>Date of Last Promotion</span></td>
                                <td class="whitebg"><input type="date" name="DateofLastPromotion" id="DateofLastPromotion" /></td>
                            </tr>
                            <tr>
                                <td><span>26.</span></td>
                                <td><span>Employer</span></td>
                                <td><select name="Employer" id="Employer" onload="loadFieldOptions(\'Employer\')" onchange="changeRelatedFieldOptions(\'Employer\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>27.</span></td>
                                <td><span>Employment Status</span></td>
                                <td class="whitebg"><select name="EmploymentStatus" id="EmploymentStatus" onload="loadFieldOptions(\'EmploymentStatus\')" onchange="changeRelatedFieldOptions(\'EmploymentStatus\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>28.</span></td>
                                <td><span>Registered Disability</span></td>
                                <td><select name="RegisteredDisability" id="RegisteredDisability" onload="loadFieldOptions(\'RegisteredDisability\')" onchange="changeRelatedFieldOptions(\'RegisteredDisability\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>29.</span></td>
                                <td><span>Contacts of Employee</span></td>
                                <td class="whitebg"><textarea name="ContactsofEmployee" id="ContactsofEmployee"></textarea></td>
                            </tr>
                            <tr>
                                <td><span>30.</span></td>
                                <td><span>Next of Kin</span></td>
                                <td><input type="text" name="NextofKin" id="NextofKin"/></td>
                            </tr>
                            <tr>
                                <td><span>31.</span></td>
                                <td><span>Relationship to Next of Kin</span></td>
                                <td class="whitebg"><select name="RelationshiptoNextofKin" id="RelationshiptoNextofKin" onload="loadFieldOptions(\'RelationshiptoNextofKin\')" onchange="changeRelatedFieldOptions(\'RelationshiptoNextofKin\')"></select></td>
                            </tr>
                            <tr>
                                <td><span>32.</span></td>
                                <td><span>Contacts of Next of Kin</span></td>
                                <td><textarea name="ContactsofNextofKin" id="ContactsofNextofKin"></textarea></td>
                            </tr>
                        </tbody>
                </table>
                '),
        );
        return $this->forms;
    }
	public function load(ObjectManager $manager)
	{
        // Populate dummy forms
        $this->addDummyForms();
        // Seek dummy fields
        $loadFieldData = new LoadFieldData();
        $loadFieldData->addDummyFields();
        $dummyFields = $loadFieldData->getFields();

        foreach($this->forms as $key=>$humanResourceForm) {
            $form = new Form();
            $form->setName($humanResourceForm['name']);
            $form->setTitle($humanResourceForm['name']);
            $form->setHypertext($humanResourceForm['hypertext']);
            $this->addReference(strtolower(str_replace(' ','',$humanResourceForm['name'])).'-form', $form);
            $manager->persist($form);
            // Add Field Members for the form created
            $sort=1;
            foreach($dummyFields as $key => $dummyField)
            {
                $formMember = new FormFieldMember();
                $formMember->setField($manager->merge($this->getReference( strtolower(str_replace(' ','',$dummyField['name'])).'-field' )));
                $formMember->setForm( $manager->merge($this->getReference(strtolower( str_replace(' ','',$humanResourceForm['name'])). '-form')) );
                $formMember->setSort($sort++);
                $referenceName = strtolower(str_replace(' ','',$humanResourceForm['name']).str_replace(' ','',$dummyField['name'])).'-form-field-member';
                $this->addReference($referenceName, $formMember);
                $manager->persist($formMember);
            }
        }
		$manager->flush();
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
		return 5;
	}

}
