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
                'title'=>'Public Employee Form'),
            1=>Array(
                'name'=>'Private Employee Form',
                'title'=>'Private Employee Form'),
            2=>Array(
                'name'=>'Hospital Employee Form',
                'title'=>'Hospital Employee Form'),
            3=>Array(
                'name'=>'Training Institution Employee Form',
                'title'=>'Training Institution Employee Form'),
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
            $form->setDatecreated(new \DateTime('now'));
            $this->addReference(strtolower($humanResourceForm['name']).'-form', $form);
            $manager->persist($form);
            // Add Field Members for the form created
            $sort=1;
            foreach($dummyFields as $key => $dummyField)
            {
                $formMember = new FormFieldMember();
                $formMember->setField($manager->merge($this->getReference( strtolower(str_replace(' ','',$dummyField['name'])).'-field' )));
                $formMember->setForm( $manager->merge($this->getReference(strtolower($humanResourceForm['name']). '-form')) );
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
