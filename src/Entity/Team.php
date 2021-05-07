<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Driver[]
     * @ORM\OneToMany(targetEntity="App\Entity\Driver", mappedBy="team")
     */
    private $drivers;

    public function __construct()
    {
        $this->drivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Driver[]
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * @param Driver $driver
     * @return Team
     */
    public function addDriver(Driver $driver): Team
    {
        $this->drivers = $driver;

        return $this;
    }

    /**
     * @param Driver[] $drivers
     * @return Team
     */
    public function setDrivers(array $drivers): Team
    {
        $this->drivers = $drivers;

        return $this;
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
}
