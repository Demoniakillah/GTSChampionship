<?php

namespace App\Entity;

use App\Repository\DriverRaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Entity(repositoryClass=DriverRaceRepository::class)
 * @ORM\Table(uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="driver_race_idx",
 *              columns={"driver","race"}
 *          )
 *     })
 * @ORM\HasLifecycleCallbacks()
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
     *
     */
    public const PENDING = 0;
    /**
     *
     */
    public const VALIDATED = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $totalTime = '00:00:000';

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $bestLap = '00:00:000';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $startPosition = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $finishPosition = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $finishStatus = self::FINISHED;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $bonus = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $penalty = 0;

    /**
     * @var Pool|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Pool")
     * @ORM\JoinColumn(name="pool", referencedColumnName="id")
     */
    private ?Pool $pool = null;

    /**
     * @var Driver|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="races")
     * @ORM\JoinColumn(name="driver", referencedColumnName="id")
     */
    private ?Driver $driver = null;

    /**
     * @var Car|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="races")
     * @ORM\JoinColumn(name="car", referencedColumnName="id")
     */
    private ?Car $car = null;

    /**
     * @var Race|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="driverRaces")
     * @ORM\JoinColumn(name="race", referencedColumnName="id", onDelete="CASCADE")
     */
    private ?Race $race = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $inscriptionStatus = self::PENDING;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $validationToken = null;

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
    public function setFinishStatus(?int $finishStatus = self::FINISHED): self
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
     * @param int $finishPosition
     * @return DriverRace
     */
    public function setFinishPosition(int $finishPosition): DriverRace
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
    public function setPenalty(int $penalty): self
    {
        $this->penalty = $penalty;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasBennValidated():bool
    {
        return $this->inscriptionStatus === self::VALIDATED;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return
            $this->isValidInscription()
            &&
            $this->finishStatus === self::FINISHED
            &&
            is_int($this->startPosition)
            &&
            is_int($this->finishPosition)
            ;
    }

    /**
     * @return bool
     */
    public function isValidInscription(): bool
    {
        return
            $this->pool instanceof Pool
            &&
            $this->driver instanceof Driver
            &&
            $this->car instanceof Car;
    }

    /**
     * @return bool
     */
    public function isValidButMissing(): bool
    {
        return
            $this->isValidInscription()
            &&
            $this->isMissing();
    }

    /**
     * @return bool
     */
    public function isValidButDisconnected(): bool
    {
        return
            $this->isValidInscription()
            &&
            $this->isDisconnected()
            &&
            is_int($this->startPosition);
    }

    /**
     * @return bool
     */
    public function isDisconnected(): bool
    {
        return $this->finishStatus === self::DISCONNECTED;
    }

    /**
     * @return bool
     */
    public function isMissing(): bool
    {
        return $this->finishStatus === self::MISSING;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        if ($this->isValid()) {
            return $this->pool->getPoints()[$this->finishPosition] + $this->bonus - $this->penalty;
        }
        return $this->pool->getPoints()[count($this->pool->getPoints()) - 1] + $this->bonus - $this->penalty;
    }

    public function getFinalPosition(): int
    {
        $results = [];
        foreach ($this->getRace()->getDriverRaces() as $driverRace){
            $results[] = ['psn'=>$driverRace->getDriver()->getPsn(),'points'=>$driverRace->getPoints()];
        }
        usort($results, static function($a,$b){
            return $a['points'] < $b['points'];
        });
        $psn = $this->getDriver()->getPsn();
        $results = array_filter($results, static function($a) use ($psn){
            return $a['psn'] === $psn;
        });
        return key($results);
    }

    /**
     * @ORM\PrePersist()
     */
    public function setStartPositionOnPersist():void
    {
        $startPosition = 0;
        foreach ($this->race->getDriverRaces() as $driverRace){
            if( $this->pool  instanceof Pool && $driverRace->hasBennValidated() && $driverRace->getPool() instanceof Pool && $driverRace->getPool()->getId() === $this->pool->getId()){
                $startPosition++;
            }
        }
        $this->startPosition = $startPosition;
    }

    /**
     * @return int
     */
    public function getInscriptionStatus(): int
    {
        return $this->inscriptionStatus;
    }

    /**
     * @param int $inscriptionStatus
     * @return $this
     */
    public function setInscriptionStatus(int $inscriptionStatus): self
    {
        $this->inscriptionStatus = $inscriptionStatus;

        return $this;
    }

    /**
     * @return $this
     */
    public function validateInscription():self
    {
        $this->inscriptionStatus = self::VALIDATED;

        return $this;
    }

    public function getValidationToken(): ?string
    {
        return $this->validationToken;
    }

    public function setValidationToken(?string $validationToken): self
    {
        $this->validationToken = $validationToken;

        return $this;
    }
    
}
