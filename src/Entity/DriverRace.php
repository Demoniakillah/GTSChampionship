<?php

namespace App\Entity;

use App\Repository\DriverRaceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DriverRaceRepository::class)
 * @ORM\Table(uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="driver_race_idx",
 *              columns={"driver","race"}
 *          )
 *     })
 */
class DriverRace
{
    /**
     *
     */
    public const FINISHED = 0;
    /**
     *
     */
    public const DISCONNECTED = 1;
    /**
     *
     */
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
    private $finishPosition;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="drivers")
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
     * @var Race
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="driverRaces")
     * @ORM\JoinColumn(name="race", referencedColumnName="id", onDelete="CASCADE")
     */
    private $race;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->driver->getPsn();
    }

    /**
     * @return Pool
     */
    public function getPool(): ?Pool
    {
        return $this->pool;
    }

    /**
     * @return Car
     */
    public function getCar(): ?Car
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
     * @param Pool|null $pool
     * @return DriverRace
     */
    public function setPool(Pool $pool = null): DriverRace
    {
        $this->pool = $pool;

        return $this;
    }

    /**
     * @return Driver
     */
    public function getDriver(): ?Driver
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
    public function getRace(): ?Race
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTotalTime(): ?string
    {
        return $this->totalTime;
    }

    /**
     * @param string|null $totalTime
     * @return $this
     */
    public function setTotalTime(?string $totalTime = '00:00:000'): self
    {
        $this->totalTime = $totalTime;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBestLap(): ?string
    {
        return $this->bestLap;
    }

    /**
     * @param string|null $bestLap
     * @return $this
     */
    public function setBestLap(?string $bestLap = '00:00:000'): self
    {
        $this->bestLap = $bestLap;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStartPosition(): ?int
    {
        return $this->startPosition;
    }

    /**
     * @param int|null $startPosition
     * @return $this
     */
    public function setStartPosition(?int $startPosition): self
    {
        $this->startPosition = $startPosition;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFinishStatus(): ?int
    {
        return $this->finishStatus;
    }

    /**
     * @param int|null $finishStatus
     * @return $this
     */
    public function setFinishStatus(?int $finishStatus): self
    {
        $this->finishStatus = $finishStatus;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFinishPosition()
    {
        return $this->finishPosition;
    }

    /**
     * @param mixed $finishPosition
     * @return DriverRace
     */
    public function setFinishPosition($finishPosition)
    {
        $this->finishPosition = $finishPosition;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBonus(): ?int
    {
        return $this->bonus;
    }

    /**
     * @param int|null $bonus
     * @return $this
     */
    public function setBonus(?int $bonus): self
    {
        $this->bonus = $bonus;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPenalty(): ?int
    {
        return $this->penalty;
    }

    /**
     * @param int|null $penalty
     * @return $this
     */
    public function setPenalty(?int $penalty): self
    {
        $this->penalty = $penalty;

        return $this;
    }
}
