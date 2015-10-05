<?php

namespace Dime\TimetrackerBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Dime\TimetrackerBundle\Entity\Setting
 *
 * @UniqueEntity(fields={"name", "namespace", "user"})
 * @ORM\Table(
 *      name="settings",
 *      uniqueConstraints={ @ORM\UniqueConstraint(name="unique_setting_name_namespace_user", columns={"`name`", "namespace", "user_id"}) }
 * )
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\SettingRepository")
 */
class Setting extends Entity
{
    /**
     * @var string $name
     *
     * @Assert\NotNull()
     * @ORM\Column(type="string", nullable=false, length=255)
     */
    protected $name;

    /**
     * @var string $namespace
     *
     * @Assert\NotNull()
     * @ORM\Column(type="string", nullable=false, length=255)
     */
    protected $namespace;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $value;

    /**
     * Set name
     *
     * @param  string   $name
     * @return Setting
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
     * Set namespace
     *
     * @param  string   $namespace
     * @return Setting
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set value
     *
     * @param  string  $value
     * @return Setting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
