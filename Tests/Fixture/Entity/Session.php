<?php

namespace Itr\ResponseBuilderBundle\Tests\Fixture\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 */
class Session
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $session;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set session
     *
     * @param string $session
     * @return Session
     */
    public function setSession($session)
    {
        $this->session = $session;
    
        return $this;
    }

    /**
     * Get session
     *
     * @return string 
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Session
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
     * @return Session
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
     * @return Session
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
}
