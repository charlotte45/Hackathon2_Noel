<?php

namespace WCS\HackathonNoelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gift
 *
 * @ORM\Table(name="gift")
 * @ORM\Entity(repositoryClass="WCS\HackathonNoelBundle\Repository\GiftRepository")
 */
class Gift
{
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Child", inversedBy="gifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $child;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="done", type="boolean")
     */
    private $done;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Gift
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
     * Set done
     *
     * @param boolean $done
     *
     * @return Gift
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return bool
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * Set child
     *
     * @param \WCS\HackathonNoelBundle\Entity\Child $child
     *
     * @return Gift
     */
    public function setChild(\WCS\HackathonNoelBundle\Entity\Child $child = null)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child
     *
     * @return \WCS\HackathonNoelBundle\Entity\Child
     */
    public function getChild()
    {
        return $this->child;
    }
}
