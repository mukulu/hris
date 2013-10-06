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
namespace Hris\FormBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Schema\Table;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Tests\Common\Annotations\False;
use Doctrine\Tests\Common\Annotations\True;
use Gedmo\Mapping\Annotation as Gedmo;

use Hris\FormBundle\Entity\ResourceTableFieldMember;
use Hris\RecordsBundle\Entity\Record;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\FormBundle\Entity\ResourceTable
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_resourcetable")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\ResourceTableRepository")
 */
class ResourceTable
{
    /**
     * Returns standard resource table name to use
     * @var string $fieldKey
     */
    private static $standardResourceTableName='_resource_all_fields';

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string $uid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;

    /**
     * @var string $name
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean isGenerating
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="isGenerating", type="boolean", nullable=True)
     */
    private $isgenerating;
    
    /**
     * @var ResourceTableFieldMember $resourceTableFieldMember
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\ResourceTableFieldMember", mappedBy="resourceTable",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $resourceTableFieldMember;

    /**
     * @var \DateTime $datecreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime")
     */
    private $datecreated;

    /**
     * @var \DateTime $lastupdated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="lastupdated", type="datetime", nullable=true)
     */
    private $lastupdated;

    /**
     * @var \DateTime $lastgenerated
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="lastgenerated", type="datetime", nullable=true)
     */
    private $lastgenerated;

    /**
     * @var string $messagelog
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="messagelog", type="text", nullable=true)
     */
    private $messagelog;

    /**
     * Field position in the form counter
     */
    private $sort;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ResourceTable
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return ResourceTable
     */
    public function setDatecreated($datecreated)
    {
        $this->datecreated = $datecreated;
    
        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Field
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isgenerating
     *
     * @param boolean $isgenerating
     * @return ResourceTable
     */
    public function setIsgenerating($isgenerating)
    {
        $this->isgenerating = $isgenerating;

        return $this;
    }

    /**
     * Get isgenerating
     *
     * @return boolean
     */
    public function getIsgenerating()
    {
        return $this->isgenerating;
    }

    /**
     * Get datecreated
     *
     * @return \DateTime 
     */
    public function getDatecreated()
    {
        return $this->datecreated;
    }

    /**
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return ResourceTable
     */
    public function setLastupdated($lastupdated)
    {
        $this->lastupdated = $lastupdated;
    
        return $this;
    }

    /**
     * Get lastupdated
     *
     * @return \DateTime 
     */
    public function getLastupdated()
    {
        return $this->lastupdated;
    }

    /**
     * Set lastgenerated
     *
     * @param \DateTime $lastgenerated
     * @return ResourceTable
     */
    public function setLastgenerated($lastgenerated)
    {
        $this->lastgenerated = $lastgenerated;

        return $this;
    }

    /**
     * Get lastgenerated
     *
     * @return \DateTime
     */
    public function getLastgenerated()
    {
        return $this->lastgenerated;
    }

    /**
     * Set messagelog
     *
     * @param string $messagelog
     * @return ResourceTable
     */
    public function setMessageLog($messagelog)
    {
        $this->messagelog = $messagelog;

        return $this;
    }

    /**
     * Get messagelog
     *
     * @return string
     */
    public function getMessageLog()
    {
        return $this->messagelog;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return ResourceTable
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    
        return $this;
    }

    /**
     * Get uid
     *
     * @return string 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Add field
     *
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $this->sort += 1;
        $this->resourceTableFieldMember[] = new ResourceTableFieldMember($this, $field, $this->sort);
    }

    /**
     * Get field
     *
     * @return Collection
     */
    public function getField()
    {
        $fieldObjects = NULL;
        if(!empty($this->resourceTableFieldMember)) {
            foreach( $this->resourceTableFieldMember as $key => $fieldMember ) {
                $fieldObjects[] = $fieldMember->getField();
            }
        }else {
            $fieldObjects = 0;
        }
        return $fieldObjects;
    }

    /**
     * Add resourceTableFieldMember
     *
     * @param ResourceTableFieldMember $resourceTableFieldMember
     * @return ResourceTable
     */
    public function addResourceTableFieldMember(ResourceTableFieldMember $resourceTableFieldMember)
    {
        $this->resourceTableFieldMember[] = $resourceTableFieldMember;
    
        return $this;
    }

    /**
     * Remove resourceTableFieldMember
     *
     * @param ResourceTableFieldMember $resourceTableFieldMember
     */
    public function removeResourceTableFieldMember(ResourceTableFieldMember $resourceTableFieldMember)
    {
        $this->resourceTableFieldMember->removeElement($resourceTableFieldMember);
    }

    /**
     * Get resourceTableFieldMember
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResourceTableFieldMember()
    {
        return $this->resourceTableFieldMember;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resourceTableFieldMember = new ArrayCollection();
        $this->uid = uniqid();
        $this->sort = 0;
    }

    /**
     * Returns Standard Resource Table name to use
     *
     * @return string
     */
    static public function getStandardResourceTableName()
    {
        return self::$standardResourceTableName;
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get all values from specific key in a multidimensional array
     *
     * @param $key string
     * @param $arr array
     * @return null|string|array
     */
    public function array_value_recursive($key, array $arr){
        $val = array();
        array_walk_recursive($arr, function($v, $k) use($key, &$val){if($k == $key) array_push($val, $v);});
        return count($val) > 1 ? $val : array_pop($val);
    }

    /**
     *  Return date difference
     *
     * @param $dformat
     * @param $endDate
     * @param $beginDate
     * @return int
     */
    public function dateDiff($dformat, $endDate, $beginDate) {

        $date_parts1 = explode($dformat, $beginDate);
        $date_parts2 = explode($dformat, $endDate);
        $start_date = gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
        $end_date = gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
        return $end_date - $start_date;
    }

    /**
     * Conversion of strings to camel notation
     *
     * @param $str
     * @param bool $capitalizeFirst
     * @param string $allowed
     * @return mixed
     */
    public function strtocamel($str, $capitalizeFirst = true, $allowed = 'A-Za-z0-9') {
        return preg_replace(
            array(
                '/([A-Z][a-z])/e',
                '/([a-zA-Z])([a-zA-Z]*)/e',
                '/[^'.$allowed.']+/e',
                '/^([a-zA-Z])/e'
            ),
            array(
                '" ".$1',
                'strtoupper("$1").strtolower("$2")',
                '',
                'strto'.($capitalizeFirst ? 'upper' : 'lower').'("$1")'
            ),
            $str
        );
    }

    /**
     * Get isResourceTableOutdated
     *
     * @param $entityManager
     * @return boolean
     */
    public function isResourceTableOutdated($entityManager)
    {
        $queryBuilder = $entityManager->createQueryBuilder();
        $lastupdated = $queryBuilder->select('MAX(record.lastupdated) as lastupdated')->from('HrisRecordsBundle:Record', 'record')->getQuery()->getResult();
        $lastupdated = $this->array_value_recursive('lastupdated',$lastupdated);
        $lastupdatedObject = new \DateTime($lastupdated);
        if($lastupdatedObject > $this->lastgenerated ) {
            $resourceTableOutdated = True;
        }else {
            $resourceTableOutdated = False;
        }
        return $resourceTableOutdated;
    }

    /**
     * Get isResourceTableCompletelyGenerated
     *
     * @param $entityManager
     * @return boolean
     */
    public function isResourceTableCompletelyGenerated($entityManager) {
        $generatedCompletely=NULL;
        $schemaManager = $entityManager->getConnection()->getSchemaManager();
        //Prepare database name
        $resourceTableName = '_resource_'. str_replace(' ','_',trim(strtolower($this->getName())));

        if( $schemaManager->tablesExist($resourceTableName) ) {
            $recordTableName = $entityManager->getClassMetadata('HrisRecordsBundle:Record')->getTableName();
            $recordsCountSQL = "SELECT COUNT(".$resourceTableName.".instance) AS resourceinstance, (SELECT COUNT(".$recordTableName.".instance) FROM ".$recordTableName.") AS valueinstance FROM ".$resourceTableName;
            $resourceAndValueInstances = $entityManager->getConnection()->fetchAssoc($recordsCountSQL);
            if(intval($resourceAndValueInstances['resourceinstance'])==intval($resourceAndValueInstances['valueinstance'])) {
                $generatedCompletely=True;
            }
        }else {
            $generatedCompletely=False;
        }
        return $generatedCompletely;
    }

    /**
     * Generate resource table
     *
     * @param $entityManager
     * @return string
     */
    public function generateResourceTable($entityManager) {


        $totalInsertedRecords = NULL;
        $totalResourceTableFields = NULL;
        $stopwatch = new Stopwatch();
        $stopwatch->start('resourceTableGeneration');

        $returnMessage = NULL;
        $schemaManager = $entityManager->getConnection()->getSchemaManager();
        $resourceTableName = '_resource_'. str_replace(' ','_',trim(strtolower($this->getName())));//Prepare database name


        if( $this->getIsgenerating() == False && ($this->isResourceTableOutdated($entityManager) == True || $this->isResourceTableCompletelyGenerated($entityManager) == False) ) {
            /*
             * Resource table is out dated, was not completely generated
             */

            //Switch state to generating
            $this->setIsgenerating(True);
            $entityManager->persist($this);
            try {
                $entityManager->flush();
            } catch (\Doctrine\Orm\NoResultException $e) {
                //@todo Cross-check, if database is left in generating state, it'll never be udpated ever.
                $error = "Error in Changing State to generating";
                echo $error;
                return False;
            }

            if($this->isResourceTableOutdated($entityManager)) {
                $this->messagelog ="Regeneration Trigger: Outdated Resource Table.\n";
            }elseif( $this->isResourceTableCompletelyGenerated($entityManager) == False ) {
                $this->messagelog = "Regeneration Trigger:Incomplete Resource Tabe.\n";
            }else {
                $this->messagelog = '';
            }

            /**
             * @var $resourceTable String
             */

            // Cleanup any residue temporary resourcetable left
            if( $schemaManager->tablesExist($resourceTableName.'_temporary') ) {
                $schemaManager->dropTable($resourceTableName.'_temporary');
            }
            $resourceTable = new Table($resourceTableName.'_temporary');//Create database table

            // Create primary key
            $resourceTable->addColumn('id', "integer",array('nullable'=>true,'precision'=>0, 'scale'=>0));
            $resourceTable->addColumn('instance', "string",array('length'=>64, 'notnull'=>false));
            $resourceTable->setPrimaryKey(array('id'),'IDX_'.uniqid(''));
            $resourceTable->addIndex(array('id'),'IDX_'.uniqid(''));

            // Create other column in the resource table
            foreach($this->getResourceTableFieldMember() as $resourceTableKey=> $resourceTableFieldMember) {
                $field = $resourceTableFieldMember->getField();

                if($field->getDataType()->getName() == "String" ) {
                    $resourceTable->addColumn($field->getName(), "string",array('length'=>64, 'notnull'=>false));
                }elseif($field->getDataType()->getName() == "Integer") {
                    $resourceTable->addColumn($field->getName(), "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
                }elseif($field->getDataType()->getName() == "Double") {
                    $resourceTable->addColumn($field->getName(), "float",array('notnull'=>false,'precision'=>0, 'scale'=>0));
                }elseif($field->getDataType()->getName() == "Date") {
                    $resourceTable->addColumn($field->getName(), "date",array('notnull'=>false,'precision'=>0, 'scale'=>0));
                    // Additional analysis columns
                    //$resourceTable->addColumn($field->getName().'_day', "string",array('length'=>64, 'notnull'=>false));
                    //$resourceTable->addColumn($field->getName().'_month_number', "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
                    $resourceTable->addColumn($field->getName().'_month_text', "string",array('length'=>64, 'notnull'=>false));
                    $resourceTable->addColumn($field->getName().'_year', "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
                    //$resourceTable->addColumn($field->getName().'_month_and_year', "string",array('length'=>64, 'notnull'=>false));

                }
                // @todo implement after creation of history date class
                // Add History date field for fields with history
                if($field->getHashistory()== True) {
                    $resourceTable->addColumn($field->getName().'_last_updated', "datetime",array('notnull'=>false,'precision'=>0, 'scale'=>0));
                    //$resourceTable->addColumn($field->getName().'_last_updated_day', "string",array('length'=>64, 'notnull'=>false));
                    //$resourceTable->addColumn($field->getName().'_last_updated_month_number', "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
                    $resourceTable->addColumn($field->getName().'_last_updated_month_text', "string",array('length'=>64, 'notnull'=>false));
                    $resourceTable->addColumn($field->getName().'_last_updated_year', "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
                    //$resourceTable->addColumn($field->getName().'_last_updated_month_and_year', "string",array('length'=>64, 'notnull'=>false));
                }
                $totalResourceTableFields++;
            }

            // Make OrganisationunitLevels of orgunit
            $organisationunitLevels = $entityManager->createQuery('SELECT DISTINCT organisationunitLevel FROM HrisOrganisationunitBundle:OrganisationunitLevel organisationunitLevel ORDER BY organisationunitLevel.level ')->getResult();
            foreach($organisationunitLevels as $organisationunitLevelKey=>$organisationunitLevel) {
                $organisationunitLevelName = "level".$organisationunitLevel->getLevel()."_".str_replace(',','_',str_replace('.','_',str_replace('/','_',str_replace(' ','_',$organisationunitLevel->getName())))) ;
                $resourceTable->addColumn($organisationunitLevelName, "string",array('length'=>64, 'notnull'=>false));
            }

            // Make OrganisationunitGroupsets Column
            $organisationunitGroupsets = $entityManager->getRepository('HrisOrganisationunitBundle:OrganisationunitGroupset')->findAll();
            foreach($organisationunitGroupsets as $organisationunitGroupsetKey=>$organisationunitGroupset) {
                $resourceTable->addColumn($organisationunitGroupset->getName(), "string",array('length'=>64, 'notnull'=>false));
            }
            // Calculated fields
            // @todo implement calculated fields feature and remove hard-coding
            $resourceTable->addColumn("Age", "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
            //$resourceTable->addColumn("Age_group", "string",array('length'=>64, 'notnull'=>false));
            $resourceTable->addColumn("Employment_duration", "string",array('length'=>64, 'notnull'=>false));
            //$resourceTable->addColumn("Retirement_date", "date",array('notnull'=>false,'precision'=>0, 'scale'=>0));
            //$resourceTable->addColumn('retirement_date_day', "string",array('length'=>64, 'notnull'=>false));
            //$resourceTable->addColumn('retirement_date_month_number', "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
            $resourceTable->addColumn('Retirement_date_month_text', "string",array('length'=>64, 'notnull'=>false));
            $resourceTable->addColumn('Retirement_date_year', "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
            //$resourceTable->addColumn('retirement_date_month_and_year', "string",array('length'=>64, 'notnull'=>false));

            // Form and Organisationunit name
            $resourceTable->addColumn("Organisationunit_name", "string",array('length'=>64, 'notnull'=>false));
            $resourceTable->addColumn("Form_name", "string",array('length'=>64, 'notnull'=>false));

            $resourceTable->addColumn('Organisationunit_id', "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
            $resourceTable->addColumn('Form_id', "integer",array('notnull'=>false,'precision'=>0, 'scale'=>0));
            $resourceTable->addColumn("Lastupdated", "datetime",array('notnull'=>false,'precision'=>0, 'scale'=>0));

            // Creating table
            $schemaManager->createTable($resourceTable);
            unset($resourceTable);
            $schemaGenerationLap = $stopwatch->lap('resourceTableGeneration');
            $schemaGenerationDuration = round(($schemaGenerationLap->getDuration()/1000),2);

            $this->messagelog .='Operation: Table named '. $resourceTableName.' with '. $totalResourceTableFields ." Fields Generated in ".$schemaGenerationDuration." seconds.\n";

            // Populating data into created table
            $queryBuilder = $entityManager->createQueryBuilder()->select('record')->from('HrisRecordsBundle:Record', 'record')
                ->join('record.organisationunit', 'organisationunit')
                ->join('record.form', 'form')
                ->join('organisationunit.organisationunitStructure','organisationunitStructure')
                ->getQuery();
            try {
                $records = $queryBuilder->getResult();
            } catch (\Doctrine\Orm\NoResultException $e) {
                echo 'Error in returning Data Values';
            }
            if (!empty($records)) {

                $dataArray=NULL;
                $id=0;
                //Prepare field Option map, converting from stored FieldOption key in record value array to actual text value
                $fieldOptions = $entityManager->getRepository('HrisFormBundle:FieldOption')->findAll();
                foreach ($fieldOptions as $fieldOptionKey => $fieldOption) {
                    $recordFieldOptionKey = ucfirst(Record::getFieldOptionKey());
                    $fieldOptionMap[call_user_func_array(array($fieldOption, "get${recordFieldOptionKey}"),array()) ] =   $fieldOption->getValue();
                }
                foreach ($records as $recordKey => $record) {
                    $currentInstance = $record->getInstance();
                    $dataValue = $record->getValue();
                    $id++;
                    $dataArray['id']=$id;
                    $age=NULL;
                    $retirementDate=NULL;
                    $employmentDuration=NULL;
                    $dataArray['instance'] = $record->getInstance();
                    foreach($this->getResourceTableFieldMember() as $resourceTableKey=> $resourceTableFieldMember) {
                        $field = $resourceTableFieldMember->getField();
                        // Field Options
                        /**
                         * Made dynamic, on which field column is used as key, i.e. uid, name or id.
                         */
                        // Translates to $field->getUid()
                        // or $field->getUid() depending on value of $recordKeyName
                        $recordFieldKey = ucfirst(Record::getFieldKey());
                        $valueKey = call_user_func_array(array($field, "get${recordFieldKey}"),array());

                        if($field->getIsCalculated()){

                            if(preg_match_all('/\#{([^\}]+)\}/',$field->getCalculatedExpression(),$match)) {
                                $fields = $entityManager->getRepository('HrisFormBundle:Field')->findOneBy (
                                    array('name' => $match[1][0])
                                );
                                $valueKey = call_user_func_array(array($fields, "get${recordFieldKey}"),array());
                            }
                        }

                        if(isset($dataValue[$valueKey])) {

                            $dataArray[$field->getName()]=$dataValue[$valueKey];
                            if ($field->getInputType()->getName() == 'Select') {

                                if (!empty($dataValue[$valueKey])){
                                    // Resolve actual value from stored key
                                    $dataArray[$field->getName()] = trim($fieldOptionMap[$dataValue[$valueKey]]);
                                } else{
                                    $dataArray[$field->getName()] = NULL;
                                }
                            }else if ($field->getInputType()->getName() == 'Date') {

                                if($field->getIsCalculated() == true){

                                        if(!empty($dataValue[$valueKey])) {
                                            $displayValue = new \DateTime($dataValue[$valueKey]['date'],new \DateTimeZone ($dataValue[$valueKey]['timezone']));
                                            //var_dump($field->getCalculatedExpression());
                                            $datavalue = str_replace($match[0][0],$displayValue->format('Y-m-d'),$field->getCalculatedExpression());

                                            $dataArray[$field->getName()] = eval("return $datavalue;");
                                            //$dataArray[$field->getName()] = trim($displayValue->format('Y-m-d H:i:s.u')); //working on date format fix
                                            //$dataArray[$field->getName().'_month_text'] = trim($displayValue->format('F'));
                                            //$dataArray[$field->getName().'_year'] = trim($displayValue->format('Y'));
                                        }else{
                                        $dataArray[$field->getName()] = NULL;
                                    }

                                }else{
                                    if(!empty($dataValue[$valueKey])) {
                                        $displayValue = new \DateTime($dataValue[$valueKey]['date'],new \DateTimeZone ($dataValue[$valueKey]['timezone']));
                                        $dataArray[$field->getName()] = trim($displayValue->format('Y-m-d')); //working on date format fix
                                        //$dataArray[$field->getName().'_day'] = trim($displayValue->format('l'));
                                        //$dataArray[$field->getName().'_month_number'] = trim($displayValue->format('m'));
                                        $dataArray[$field->getName().'_month_text'] = trim($displayValue->format('F'));
                                        $dataArray[$field->getName().'_year'] = trim($displayValue->format('Y'));
                                        //$dataArray[$field->getName().'_month_and_year'] = trim($displayValue->format('F Y'));
                                    }else{
                                        $dataArray[$field->getName()] = NULL;
                                    }
                                }

                                // @todo implement calculated fields feature and remove hard-coding
                                // Set Calculated Retirement Date Field values
                                if ($field->getName()=="Birthdate"){
                                    $birthDate = new \DateTime($dataValue[$valueKey]['date'],new \DateTimeZone ($dataValue[$valueKey]['timezone']));
                                    $birthDate = $birthDate->format('Y-m-d');
                                    $age = round($this->dateDiff("-", date("Y-m-d"), $birthDate) / 365, 0);
                                    $retirementDate = new \DateTime($dataValue[$valueKey]['date'],new \DateTimeZone ($dataValue[$valueKey]['timezone']));
                                    $retirementDate->add(new \DateInterval('P60Y'));
                                }
                                // Set Calculated Employment Date Field values
                                if ($field->getName()=="DateofFirstAppointment"){
                                    $firstAppointment = new \DateTime($dataValue[$valueKey]['date'],new \DateTimeZone ($dataValue[$valueKey]['timezone']));
                                    $firstAppointment = $firstAppointment->format('Y-m-d');
                                    $datediff = $this->dateDiff("-", date("Y-m-d"), $firstAppointment);
                                    if (round($datediff / 12, 0) < 12) {
                                        $employmentDuration = round($datediff / 12, 0) . "m";
                                    } else {
                                        $employmentDuration = floor($datediff / 365) . "y " . floor(($datediff % 365) / 30) . "m";
                                    }
                                }

                            }else {
                                if ($field->getDataType()->getName() == 'Integer') {
                                    if(!empty($dataValue[$valueKey])) {
                                        $intValue = (int)$dataValue[$valueKey];
                                        $dataArray[$field->getName()] = trim($intValue); //working on Integers format fix
                                    }else{
                                        $dataArray[$field->getName()] = NULL;
                                    }
                                }
                                else if ($field->getDataType()->getName() == 'Double') {
                                    if(!empty($dataValue[$valueKey])) {
                                        $floatValue = (float)$dataValue[$valueKey];
                                        $dataArray[$field->getName()] = trim($floatValue); //working on float format fix
                                    }else{
                                        $dataArray[$field->getName()] = NULL;
                                    }
                                }
                                else{
                                    $dataArray[$field->getName()] = substr(trim($dataValue[$valueKey]), 0, 63);
                                }
                            }

                        }else {
                            $dataArray[$field->getName()] = NULL;
                        }

                        // @todo implement after creation of history date class
                        // Add history date data for fields  with history
                        if($field->getHasHistory()==True && $field->getInputType()->getName() == 'Select' && !empty($dataValue[$valueKey])) {
                            // Fetch history date with instance same as our current data
                            $historyDates = $entityManager->getRepository('HrisRecordsBundle:HistoryDate')
                                ->findOneBy(
                                    array('instance'=>$record->getInstance(),
                                        'history'=>trim($fieldOptionMap[$dataValue[$valueKey]]),
                                        'field'=>$field
                                    )
                                );
                            //$lastHistoryDate = $historyDates[count($historyDates)-1];
                            if(!empty($historyDates)) {
                                $dataArray[$field->getName().'_last_updated'] = trim($historyDates->getPreviousdate()->format('Y-m-d H:i:s.u'));
                                //$dataArray[$field->getName().'_last_updated_day'] = trim($historyDates->getPreviousdate()->format('l'));
                                //$dataArray[$field->getName().'_last_updated_month_number'] = trim($historyDates->getPreviousdate()->format('m'));
                                $dataArray[$field->getName().'_last_updated_month_text'] = trim($historyDates->getPreviousdate()->format('F'));
                                $dataArray[$field->getName().'_last_updated_year'] = trim($historyDates->getPreviousdate()->format('Y'));
                                //$dataArray[$field->getName().'_last_updated_month_and_year'] = trim($historyDates->getPreviousdate()->format('F Y'));
                            }
                        }
                    }

                    // @todo implement calculated fields feature and remove hard-coding
                    // Fill in  calculated fields after finishing all fields in a record
                    // Calculated Fields
                    if(!empty($age)) {
                        $dataArray['Age'] = $age;
                        //$dataArray['Age_group'] = ((floor($age/5))*5) .'-'.(((floor($age/5))*5)+4);
                    }else {
                        $dataArray['Age']=NULL;
                    }
                    if(!empty($retirementDate)) {
                        //$dataArray['Retirement_date'] = trim($retirementDate->format('Y-m-d H:i:s.u'));
                        //$dataArray['retirement_date_day'] = trim($retirementDate->format('l'));
                        //$dataArray['retirement_date_month_number'] = trim($retirementDate->format('m'));
                        $dataArray['Retirement_date_month_text'] = trim($retirementDate->format('F'));
                        $dataArray['Retirement_date_year'] = trim($retirementDate->format('Y'));
                        //$dataArray['retirement_date_month_and_year'] = trim($retirementDate->format('F Y'));
                    }else {
                        //$dataArray['Retirement_date'] =NULL;
                    }
                    if(!empty($employmentDuration)) {
                        $dataArray['Employment_duration'] = $employmentDuration;
                    }else {
                        $dataArray['Employment_duration'] =NULL;
                    }
                    // Fill in Levels
                    foreach($organisationunitLevels as $organisationunitLevelKey=>$organisationunitLevel) {
                        $organisationunitLevelName = str_replace(' ','_',"level".$organisationunitLevel->getLevel()."_".str_replace(',','_',str_replace('.','_',str_replace('/','_',$organisationunitLevel->getName())))); ;
                        $organisationunitStructure=$record->getOrganisationunit()->getOrganisationunitStructure();

                        $nLevelParent=$organisationunitStructure->getParentByNLevelsBack($record->getOrganisationunit(),($organisationunitStructure->getLevel()->getLevel()-$organisationunitLevel->getLevel()));
                        $dataArray[$organisationunitLevelName] = $nLevelParent->getLongname();

                        $thisrganisationunitLevel = $entityManager->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->findOneBy(array('level'=>$organisationunitStructure->getLevel()->getLevel()));
                        $organisationunitLevelName = str_replace(' ','_',"level".$thisrganisationunitLevel->getLevel()."_".str_replace(',','_',str_replace('.','_',str_replace('/','_',$thisrganisationunitLevel->getName())))); ;
                        $dataArray[$organisationunitLevelName] = $record->getOrganisationunit()->getLongname();
                    }
                    // Fill in Groupset Columns
                    foreach($organisationunitGroupsets as $organisationunitGroupsetKey=>$organisationunitGroupset) {
                        $organisationunitGroupsetNames=NULL;
                        foreach($organisationunitGroupset->getOrganisationunitGroup() as $organisationunitGroupKey=>$organisationunitGroup) {
                            if( $organisationunitGroup->getOrganisationunit()->contains($record->getOrganisationunit()) ) {
                                if(empty($organisationunitGroupNames)) $organisationunitGroupNames=$organisationunitGroup->getName();else $organisationunitGroupNames.=','.$organisationunitGroupNames=$organisationunitGroup->getName();
                            }
                        }
                        if(empty($organisationunitGroupNames)) $organisationunitGroupNames= NULL;
                        $dataArray[$organisationunitGroupset->getName()] = $organisationunitGroupNames;
                    }

                    // Form and Orgunit
                    $dataArray['Organisationunit_name'] = $record->getOrganisationunit()->getLongname();
                    $dataArray['Form_name'] = $record->getForm()->getName();

                    $dataArray['Organisationunit_id'] = $record->getOrganisationunit()->getId();
                    $dataArray['Form_id'] = $record->getForm()->getId();
                    $dataArray['Lastupdated'] = trim($record->getLastupdated()->format('Y-m-d H:i:s.u'));

                    $entityManager->getConnection()->insert($resourceTableName.'_temporary', $dataArray);
                    $totalInsertedRecords++;
                    unset($dataArray);
                }
            }
            $dataInsertionLap = $stopwatch->lap('resourceTableGeneration');
            $dataInsertionDuration = round(($dataInsertionLap->getDuration()/1000),2) - $schemaGenerationDuration;
            $singleDataInsertionDuration = round(($dataInsertionDuration/$totalInsertedRecords),2);
            if( $dataInsertionDuration <60 ) {
                $dataInsertionDurationMessage = round($dataInsertionDuration,2).' sec.';
            }elseif( $dataInsertionDuration >= 60 && $dataInsertionDuration < 3600 ) {
                $dataInsertionDurationMessage = round(($dataInsertionDuration/60),2) .' min.';
            }elseif( $dataInsertionDuration >=3600 && $dataInsertionDuration < 216000) {
                $dataInsertionDurationMessage = round(($dataInsertionDuration/3600),2) .' hrs';
            }else {
                $dataInsertionDurationMessage = round(($dataInsertionDuration/86400),2) .' days';
            }
            if( $singleDataInsertionDuration <60 ) {
                $singleDataInsertionDurationMessage = "(".round($singleDataInsertionDuration,2).' sec./record)';
            }elseif( $singleDataInsertionDuration >= 60 && $singleDataInsertionDuration < 3600 ) {
                $singleDataInsertionDurationMessage = "(".round(($singleDataInsertionDuration/60),2) .' min./record)';
            }elseif( $singleDataInsertionDuration >=3600 && $singleDataInsertionDuration < 216000) {
                $singleDataInsertionDurationMessage = "(".round(($singleDataInsertionDuration/3600),2) .' hrs/record)';
            }else {
                $singleDataInsertionDurationMessage = "(".round(($singleDataInsertionDuration/86400),2) .' days/record)';
            }
            $this->messagelog .= "Operation: ".$totalInsertedRecords ." Records Inserted into ". $resourceTableName." in ". $dataInsertionDurationMessage.$singleDataInsertionDurationMessage .".\n";
            /*
             * Replace existing resource table with completely regenerated temporary resource table
             */
            // Drop table if it exists
            if( $schemaManager->tablesExist($resourceTableName) ) {
                $schemaManager->dropTable($resourceTableName);
                $this->messagelog .="Operation: Dropping existent resource table\n";
            }
            $schemaManager->renameTable($resourceTableName.'_temporary', $resourceTableName);
            unset($schemaManager);
            $stopwatch->lap('resourceTableGeneration');
            $offlineDuration = round(($dataInsertionLap->getDuration()/1000),2) - ($schemaGenerationDuration+$dataInsertionDuration);

            if( $offlineDuration <60 ) {
                $offlineDurationMessage = round($offlineDuration,2).' sec.';
            }elseif( $offlineDuration >= 60 && $offlineDuration < 3600 ) {
                $offlineDurationMessage = round(($offlineDuration/60),2) .' min.';
            }elseif( $offlineDuration >=3600 && $offlineDuration < 216000) {
                $offlineDurationMessage = round(($offlineDuration/3600),2) .' hrs';
            }else {
                $offlineDurationMessage = round(($offlineDuration/86400),2) .' days';
            }
            $this->messagelog .= "Reports Offline Time: Resourcetable was offline for ". $offlineDurationMessage ."\n";

            // Update last generated after running the script
            $this->setLastgenerated(new \DateTime('now'));
            $this->setIsgenerating(False);
            $entityManager->persist($this);
            try {
                $entityManager->flush();
            } catch (\Doctrine\Orm\NoResultException $e) {
                $error = "Error Last generated";
            }
            /*
             * Check Clock for time spent
            */
            $resourceTableGenerationTime = $stopwatch->stop('resourceTableGeneration');
            $duration = $resourceTableGenerationTime->getDuration()/1000;
            unset($stopwatch);
            if( $duration <60 ) {
                $durationMessage = round($duration,2).' seconds';
            }elseif( $duration >= 60 && $duration < 3600 ) {
                $durationMessage = round(($duration/60),2) .' minutes';
            }elseif( $duration >=3600 && $duration < 216000) {
                $durationMessage = round(($duration/3600),2) .' hours';
            }else {
                $durationMessage = round(($duration/86400),2) .' hours';
            }
            $this->messagelog .= "Operation: Resource Table generation completeted in ". $durationMessage .".\n\n";
            return True;
        }else {
            $this->messagelog .= "Status: Resource Table ".$resourceTableName." is upto date.\n";
            return False;
        }
    }
    
}