<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=RaceRepository::class)
 * @ORM\Table(uniqueConstraints={
 *     @UniqueConstraint(
 *              name="unique_race_name_by_account",
 *              columns={"user_group","name"}
 *          )
 *     })
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
    private string $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $livery;

    /**
     * @var RacePoolHost[]
     * @ORM\OneToMany(targetEntity="App\Entity\RacePoolHost", mappedBy="race", cascade={"remove"})
     */
    private $poolHosts;

    /**
     * @var RacePoolCasting[]
     * @ORM\OneToMany(targetEntity="App\Entity\RacePoolCasting", mappedBy="race", cascade={"remove"})
     */
    private $poolCastings;

    /**
     * @var RacePoolSaloonLabel[]
     * @ORM\OneToMany(targetEntity="App\Entity\RacePoolSaloonLabel", mappedBy="race", cascade={"remove"})
     */
    private $poolSaloonLabels;

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
     *     joinColumns={@ORM\JoinColumn(name="race", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="car", referencedColumnName="id")}
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
     * @var string|null
     * @ORM\Column(name="more_details", type="text", nullable=true)
     */
    private ?string $moreDetails;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isEndurance = false;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private ?string $imageUrl;

    /**
     * @var UserGroup
     * @ORM\ManyToOne(targetEntity="App\Entity\UserGroup", inversedBy="races")
     * @ORM\JoinColumn(name="user_group", referencedColumnName="id")
     */
    private UserGroup $userGroup;

    /**
     * @param UserGroup $userGroup
     * @return Race
     */
    public function setUserGroup(UserGroup $userGroup): self
    {
        $this->userGroup = $userGroup;
        return $this;
    }

    /**
     * @return UserGroup
     */
    public function getUserGroup(): UserGroup
    {
        return $this->userGroup;
    }

    /**
     * @return bool
     */
    public function isPassed(): bool
    {
        $now = new DateTime();
        return
            $this->date instanceof DateTimeInterface
            &&
            $this->date->getTimestamp() < $now->getTimestamp();
    }

    /**
     * @return string
     */
    public function __toString(): string
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
    public function setMoreDetails(?string $moreDetails = ''): Race
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
    public function setConfigurations($configurations): Race
    {
        $this->configurations = $configurations;

        return $this;
    }

    /**
     * @return PersistentCollection|DriverRace[]
     */
    public function getDriverRaces()
    {
        return $this->driverRaces;
    }

    /**
     * @return RacePoolHost[]
     */
    public function getPoolHosts()
    {
        return $this->poolHosts;
    }

    /**
     * @param RacePoolHost[] $poolHosts
     * @return Race
     */
    public function setPoolHosts($poolHosts): Race
    {
        $this->poolHosts = $poolHosts;
        return $this;
    }

    /**
     * @return RacePoolCasting[]
     */
    public function getPoolCastings()
    {
        return $this->poolCastings;
    }

    /**
     * @param RacePoolCasting[] $poolCastings
     * @return Race
     */
    public function setPoolCastings($poolCastings): Race
    {
        $this->poolCastings = $poolCastings;
        return $this;
    }

    /**
     * @return RacePoolSaloonLabel[]
     */
    public function getPoolSaloonLabels()
    {
        return $this->poolSaloonLabels;
    }

    /**
     * @param RacePoolSaloonLabel[] $poolSaloonLabels
     * @return Race
     */
    public function setPoolSaloonLabels($poolSaloonLabels): Race
    {
        $this->poolSaloonLabels = $poolSaloonLabels;
        return $this;
    }

    /**
     * @param ArrayCollection|DriverRace[] $driverRaces
     * @return Race
     */
    public function setDriverRaces($driverRaces): Race
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
     * @param RaceCarConfiguration $carConfiguration
     * @return Race
     */
    public function addCarConfiguration(RaceCarConfiguration $carConfiguration): Race
    {
        $this->carConfigurations[] = $carConfiguration;

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
    public function setCars($cars): Race
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
     * Race constructor.
     * @param Race|null $race
     */
    public function __construct(Race $race = null)
    {
        if ($race instanceof self) {
            $methods = [];
            foreach (get_class_methods(self::class) as $method) {
                if (false === strpos($method, "getId") && 0 === strpos($method, "get")) {
                    $methods[$method] = str_replace('get', 'set', $method);
                }
            }
            foreach ($methods as $get => $set) {
                if (!is_null($race->$get())) {
                    $this->$set($race->$get());
                }
            }
        } else {
            $this->poolHosts = new ArrayCollection();
            $this->poolSaloonLabels = new ArrayCollection();
            $this->poolCastings = new ArrayCollection();
            $this->cars = new ArrayCollection();
            $this->driverRaces = new ArrayCollection();
            $this->configurations = new ArrayCollection();
            $this->carConfigurations = new ArrayCollection();
            $this->date = new DateTime();
        }
    }

    /**
     * @return int|null
     */
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

    /**
     * @return string
     */
    public function getName(): string
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
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface $date
     * @return $this
     */
    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLivery(): ?string
    {
        return $this->livery;
    }

    /**
     * @param string|null $livery
     * @return $this
     */
    public function setLivery(?string $livery): self
    {
        $this->livery = $livery;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsEndurance(): ?bool
    {
        return $this->isEndurance;
    }

    /**
     * @param bool $isEndurance
     * @return $this
     */
    public function setIsEndurance(bool $isEndurance): self
    {
        $this->isEndurance = $isEndurance;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl
     * @return $this
     */
    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValidForInscription(): bool
    {
        return
            !$this->cars->isEmpty()
            &&
            !empty($this->track)
            &&
            !$this->isPassed();
    }
}
