<?php

namespace Hris\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\UserBundle\Entity\User
 *
 * @ORM\Table(name="hris_user")
 * @ORM\Entity(repositoryClass="Hris\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /*
     * @todo
     * 	- Organisation unit (many)
     * 	- Phone numbers
     * 	- Job title
     * 	- date added
     *  - last updated
     */
    
    public function __construct()
    {
    	parent::__construct();
    }
}
