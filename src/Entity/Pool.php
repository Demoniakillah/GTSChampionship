<?php

namespace App\Entity;

use App\Repository\PoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=PoolRepository::class)
 * @ORM\Table(uniqueConstraints={
 *     @UniqueConstraint(
 *              name="unique_race_name_by_account",
 *              columns={"user_group","name"}
 *          )
 *     })
 *
 */
class Pool
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var array
     * @ORM\Column(name="points", type="json")
     */
    private $points;

    /**
     * @var int|null
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority = 0;

    /**
     * @var UserGroup
     * @ORM\ManyToOne(targetEntity="App\Entity\UserGroup", inversedBy="races")
     * @ORM\JoinColumn(name="user_group", referencedColumnName="id")
     */
    private UserGroup $userGroup;

    /**
     * @return int
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return Pool
     */
    public function setPriority(int $priority): Pool
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @var Driver[]
     * @ORM\OneToMany(targetEntity="App\Entity\Driver", mappedBy="pool")
     */
    private $drivers;

    /**
     * @return string
     */
    public function __toString():string
    {
        return $this->name;
    }

    /**
     * Pool constructor.
     */
    public function __construct()
    {
        $this->drivers = new ArrayCollection();
    }

    /**
     * @return Driver[]
     */
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * @return UserGroup
     */
    public function getUserGroup(): UserGroup
    {
        return $this->userGroup;
    }

    /**
     * @param UserGroup $userGroup
     * @return Pool
     */
    public function setUserGroup(UserGroup $userGroup): Pool
    {
        $this->userGroup = $userGroup;
        return $this;
    }

    /**
     * @param Driver[] $drivers
     */
    public function setDrivers(array $drivers): self
    {
        $this->drivers = $drivers;

        return $this;
    }

    /**
     * @param Driver $driver
     */
    public function addDriver(Driver $driver): void
    {
        $this->drivers[] = $driver;
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getPoints():?array
    {
        return $this->points;
    }

    /**
     * @param array $points
     * @return Pool
     */
    public function setPoints(array $points): Pool
    {
        $this->points = $points;

        return $this;
    }
}
