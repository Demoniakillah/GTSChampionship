<?php

namespace App\Entity;

use App\Repository\PoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass=PoolRepository::class)
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
     * @var int
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority;

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

    public function __toString():string
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->drivers = new ArrayCollection();
    }

    /**
     * @return Driver[]
     */
    public function getDrivers(): PersistentCollection
    {
        return $this->drivers;
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


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

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
