<?php
namespace Dime\TimetrackerBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Dime\TimetrackerBundle\Entity\Project
 *
 * @UniqueEntity(fields={"email"})
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $firstname
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $lastname;

    /**
     * @var datetime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @JMS\SerializedName("createdAt")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @JMS\SerializedName("updatedAt")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

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
     * @param  string $firstname
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
     * @param  string $lastname
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
     * Get created at datetime
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updated at datetime
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * get user as string
     *
     * @return string
     */
    public function __toString()
    {
        $user = trim($this->getFirstname() . ' ' . $this->getLastname());
        if ($this->hasEmail()) {
            $user .= empty($user) ? $this->getEmail() : ' (' . $this->getEmail() . ')';
        }

        if (empty($user)) {
            $user = $this->getId();
        }

        return $user;
    }
}
