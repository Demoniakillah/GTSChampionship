<?php

namespace App\Entity;

use App\Repository\DriverRaceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DriverRaceRepository::class)
 */
class DriverRace
{
    public const FINISHED = 0;
    public const DISCONECTED = 1;
    public const MISSING = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $totalTime;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $bestLap;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $startPosition;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $finishStatus = self::FINISHED;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bonus = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $penalty = 0;

    /**
     * @var Pool
     * @ORM\ManyToOne(targetEntity="App\Entity\Pool")
     * @ORM\JoinColumn(name="pool", referencedColumnName="id")
     */
    private $pool;

    /**
     * @var Driver
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="races")
     * @ORM\JoinColumn(name="driver", referencedColumnName="id")
     */
    private $driver;

    /**
     * @var Car
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="races")
     * @ORM\JoinColumn(name="car", referencedColumnName="id")
     */
    private $car;

    /**
     * @return Pool
     */
    public function getPool(): Pool
    {
        return $this->pool;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }

    /**
     * @param Car $car
     * @return DriverRace
     */
    public function setCar(Car $car): DriverRace
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @param Pool $pool
     * @return DriverRace
     */
    public function setPool(Pool $pool): DriverRace
    {
        $this->pool = $pool;

        return $this;
    }

    /**
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * @param Driver $driver
     * @return DriverRace
     */
    public function setDriver(Driver $driver): DriverRace
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return Race
     */
    public function getRace(): Race
    {
        return $this->race;
    }

    /**
     * @param Race $race
     * @return DriverRace
     */
    public function setRace(Race $race): DriverRace
    {
        $this->race = $race;

        return $this;
    }

    /**
     * @var Race
     */
    private $race;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalTime(): ?string
    {
        return $this->totalTime;
    }

    public function setTotalTime(?string $totalTime): self
    {
        $this->totalTime = $totalTime;

        return $this;
    }

    public function getBestLap(): ?string
    {
        return $this->bestLap;
    }

    public function setBestLap(?string $bestLap): self
    {
        $this->bestLap = $bestLap;

        return $this;
    }

    public function getStartPosition(): ?int
    {
        return $this->startPosition;
    }

    public function setStartPosition(?int $startPosition): self
    {
        $this->startPosition = $startPosition;

        return $this;
    }

    public function getFinishStatus(): ?int
    {
        return $this->finishStatus;
    }

    public function setFinishStatus(?int $finishStatus): self
    {
        $this->finishStatus = $finishStatus;

        return $this;
    }

    public function getBonus(): ?int
    {
        return $this->bonus;
    }

    public function setBonus(?int $bonus): self
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getPenalty(): ?int
    {
        return $this->penalty;
    }

    public function setPenalty(?int $penalty): self
    {
        $this->penalty = $penalty;

        return $this;
    }
}
