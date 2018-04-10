<?php

namespace MyProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Entity
 * @package MyProjectBundle\Entity
 */
abstract class Entity
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="deleted", type="integer", nullable=false, options={"default" : 0})
     */
    protected $deleted = 0;

    public function __construct()
    {
        if ($this->createdAt == null) {
            $this->setCreatedAt(new \DateTime("now"));
            $this->setUpdateAt(new \DateTime("now"));
        }
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdateAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param int $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }
}
