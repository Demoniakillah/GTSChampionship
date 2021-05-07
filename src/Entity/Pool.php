<?php

namespace App\Entity;

use App\Repository\PoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Driver[]
     * @ORM\OneToMany(targetEntity="App\Entity\Driver", mappedBy="pool")
     */
    private $drivers;

    /**
     * @return Driver[]
     */
    public function getDrivers(): array
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
}
