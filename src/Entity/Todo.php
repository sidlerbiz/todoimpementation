<?php

/*
 * This file is part of the TodoList package.
 * (c) Aleksey Mihayluk <sidlerbiz@gmail.com>
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Neo\Bundle\TodoBundle\Entity\TodoEntityInterface;
use Neo\Bundle\TodoBundle\Entity\TodoEntityTrait;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Todo implements TodoEntityInterface
{
    use TodoEntityTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    public function __construct()
    {
        $this->dueDate = new \DateTime('+1 Day');
    }

    /**
     * Get id
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get Create Date
     *
     * @return \DateTime
     */
    public function getCreateDate(): \DateTime
    {
        return $this->createDate;
    }

    /**
     * Set Create Date
     *
     * @param \DateTime $createDate
     */
    public function setCreateDate(\DateTime $createDate): void
    {
        $this->createDate = $createDate;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createDate = new \DateTime();
    }
}
