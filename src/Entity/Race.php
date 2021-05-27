<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

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
     * @ORM\Column(type="string", length=255, unique=true)
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
     * @ORM\OneToMany(targetEntity="App\Entity\DriverRace", mappedBy="race", cascade={"remove"})
     */
    private $driverRaces;

    /**
     * @var RaceConfiguration[]
     * @ORM\OneToMany(targetEntity="App\Entity\RaceConfiguration", mappedBy="race", cascade={"remove"})
     */
    private $configurations;

    /**
     * @var RaceCarConfiguration[]
     * @ORM\OneToMany(targetEntity="App\Entity\RaceCarConfiguration", mappedBy="race", cascade={"remove"})
     */
    private $carConfigurations;

    /**
     * @var string
     * @ORM\Column(name="more_details", type="text", nullable=true)
     */
    private $moreDetails;

    public function __toString():string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMoreDetails(): ?string
    {
        return $this->moreDetails;
    }

    /**
     * @param string $moreDetails
     * @return Race
     */
    public function setMoreDetails(string $moreDetails): Race
    {
        $this->moreDetails = $moreDetails;

        return $this;
    }

    /**
     * @return RaceConfiguration[]
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }

    /**
     * @param RaceConfiguration[] $configurations
     * @return Race
     */
    public function setConfigurations(array $configurations): Race
    {
        $this->configurations = $configurations;

        return $this;
    }

    /**
     * @return DriverRace[]
     */
    public function getDriverRaces()
    {
        return $this->driverRaces;
    }

    /**
     * @param DriverRace[] $driverRaces
     * @return Race
     */
    public function setDriverRaces(array $driverRaces): Race
    {
        $this->driverRaces = $driverRaces;

        return $this;
    }

    /**
     * @param RaceConfiguration $configuration
     * @return Race
     */
    public function addConfiguration(RaceConfiguration $configuration): Race
    {
        $this->configurations[] = $configuration;

        return $this;
    }

    /**
     * @return Track
     */
    public function getTrack(): ?Track
    {
        return $this->track;
    }

    /**
     * @return Car[]
     */
    public function getCars()
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

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->driverRaces = new ArrayCollection();
        $this->configurations = new ArrayCollection();
        $this->carConfigurations = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return RaceCarConfiguration[]
     */
    public function getCarConfigurations()
    {
        return $this->carConfigurations;
    }

    /**
     * @param RaceCarConfiguration[] $carConfigurations
     * @return Race
     */
    public function setCarConfigurations($carConfigurations): Race
    {
        $this->carConfigurations = $carConfigurations;

        return $this;
    }

    public function getName(): string
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
