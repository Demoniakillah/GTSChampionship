<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RaceRepository::class)
 */
class Race
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
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $casting;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $host;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $livery;

    /**
     * @var Track
     * @ORM\ManyToOne(targetEntity="App\Entity\Track")
     * @ORM\JoinColumn(name="track", referencedColumnName="id")
     */
    private $track;

    /**
     * @var Car[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Car")
     * @ORM\JoinTable(
     *     name="race_cars",
     *     joinColumns={@ORM\JoinColumn(name="car", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="race", referencedColumnName="id")}
     * )
     */
    private $cars;

    /**
     * @var DriverRace[]
     * @ORM\ManyToMany(targetEntity="App\Entity\DriverRace")
     * @ORM\JoinTable(
     *     name="race_drivers",
     *     joinColumns={@ORM\JoinColumn(name="driver", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="race", referencedColumnName="id")}
     * )
     */
    private $drivers;

    /**
     * @return Track
     */
    public function getTrack(): Track
    {
        return $this->track;
    }

    /**
     * @return Car[]
     */
    public function getCars(): array
    {
        return $this->cars;
    }

    /**
     * @param Car[] $cars
     * @return Race
     */
    public function setCars(array $cars): Race
    {
        $this->cars = $cars;

        return $this;
    }

    /**
     * @param Car $car
     * @return Race
     */
    public function addCar(Car $car): Race
    {
        $this->cars[] = $car;

        return $this;
    }

    /**
     * @param Track $track
     * @return Race
     */
    public function setTrack(Track $track): Race
    {
        $this->track = $track;

        return $this;
    }

    /**
     * @return DriverRace[]
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * @param DriverRace[] $drivers
     * @return Race
     */
    public function setDrivers(array $drivers): Race
    {
        $this->drivers = $drivers;

        return $this;
    }


    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->drivers = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCasting(): ?string
    {
        return $this->casting;
    }

    public function setCasting(?string $casting): self
    {
        $this->casting = $casting;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getLivery(): ?string
    {
        return $this->livery;
    }

    public function setLivery(?string $livery): self
    {
        $this->livery = $livery;

        return $this;
    }
}
