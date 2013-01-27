<?php

namespace Itr\ResponseBuilderBundle\Tests\Fixture\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 */
class Profile
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $fullname;

    /**
     * @var string
     */
    private $website;

    /**
     * @var string
     */
    private $bio;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var integer
     */
    private $tall;

    /**
     * @var integer
     */
    private $weight;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Itr\ResponseBuilderBundle\Tests\Fixture\Entity\Account
     */
    private $account;

    /**
     * @var mixed
     */
    private $attachment;


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
     * Set fullname
     *
     * @param string $fullname
     * @return Profile
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    
        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Profile
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return Profile
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    
        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Profile
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Profile
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set tall
     *
     * @param integer $tall
     * @return Profile
     */
    public function setTall($tall)
    {
        $this->tall = $tall;
    
        return $this;
    }

    /**
     * Get tall
     *
     * @return integer 
     */
    public function getTall()
    {
        return $this->tall;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return Profile
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    
        return $this;
    }

    /**
     * Get weight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Profile
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Profile
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set account
     *
     * @param \Itr\ResponseBuilderBundle\Tests\Fixture\Entity\Account $account
     * @return Profile
     */
    public function setAccount(\Itr\ResponseBuilderBundle\Tests\Fixture\Entity\Account $account = null)
    {
        $this->account = $account;
    
        return $this;
    }

    /**
     * Get account
     *
     * @return \Itr\ResponseBuilderBundle\Tests\Fixture\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getAccount()->getUsername();
    }

    /**
     * @param mixed $attachment
     *
     * @return Profile
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttachment()
    {
        return $this->attachment;
    }
}
