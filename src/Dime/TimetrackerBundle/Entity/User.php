<?php
namespace Dime\TimetrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Dime\TimetrackerBundle\Entity\Project
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\UserRepository")
 */
class User {

    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $duration
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $firstname;

    /**
     * @var string $duration
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $lastname;

    /**
     * email
     *
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * get user as string
     *
     * @return string
     */
    public function __toString()
    {
        $user = trim($this->getFirstname() . ' ' . $this->getLastname());
        if ($this->hasEmail())
        {
            $user .= empty($user) ? $this->getEmail() : ' (' . $this->getEmail() . ')';
        }

        if (empty($user))
        {
            $user = $this->getId();
        }

        return $user;
    }
              

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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * whether user has any value set as email
     *
     * @access public
     * @return boolean
     */
    public function hasEmail()
    {
        return 0 < strlen($this->getEmail());
    }

    /**
     * Get email
     *
     * @return email
     */
    public function getEmail()
    {
        return $this->email;
    }
}
