<?php

namespace App\Entity;

use App\Repository\DriverRaceRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Tool;

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
    private ?string $raceTime = '00:00.000';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $raceTimeMilli = 0;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $totalTime = '00:00.000';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $totalTimeMilli = 0;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $bestLap = '00:00.000';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $bestLapMilli = 0;

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
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $bonus = '00:00.000';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $bonusMilli = 0;

    /**
     * @ORM\Column(type="string", length=10,nullable=true)
     */
    private ?string $penalty = '00:00.000';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $penaltyMilli = 0;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $creationDate = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updateDate = null;

    /**
     * @ORM\PrePersist()
     * @return $this
     * */
    public function setCreationDateOnPrePersist(): self
    {
        $this->creationDate = new DateTime;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @return $this
     * */
    public function setUpdateDateOnUpdate(): self
    {
        $this->updateDate = new DateTime;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param DateTimeInterface|null $creationDate
     * @return $this
     */
    public function setCreationDate(?DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdateDate(): ?DateTimeInterface
    {
        return $this->updateDate;
    }

    /**
     * @param DateTimeInterface|null $updateDate
     * @return $this
     */
    public function setUpdateDate(?DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

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
    protected function setTotalTime(?string $totalTime = '00:00.000'): self
    {
        $this->totalTime = $totalTime;
        $this->totalTimeMilli = Tool::timeToMilli($totalTime);
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
    public function setBestLap(?string $bestLap = '00:00.000'): self
    {
        $this->bestLap = $bestLap;
        $this->bestLapMilli = Tool::timeToMilli($bestLap);

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
     * @return string|null
     */
    public function getBonus(): ?string
    {
        return $this->bonus;
    }

    /**
     * @param string|null $bonus
     * @return $this
     */
    public function setBonus(?string $bonus): self
    {
        $this->bonus = $bonus;
        $this->bonusMilli = Tool::timeToMilli($bonus);
        $this->updateTotalTime();
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPenalty(): ?string
    {
        return $this->penalty;
    }

    /**
     * @param string|null $penalty
     * @return $this
     */
    public function setPenalty(string $penalty): self
    {
        $this->penalty = $penalty;
        $this->penaltyMilli = Tool::timeToMilli($penalty);
        $this->updateTotalTime();
        return $this;
    }

    /**
     * @return bool
     */
    public function hasBeenValidated(): bool
    {
        return $this->inscriptionStatus === self::VALIDATED;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return
            $this->isValidInscription() &&
            $this->finishStatus === self::FINISHED &&
            is_int($this->startPosition) &&
            is_int($this->finishPosition);
    }

    /**
     * @return bool
     */
    public function isValidInscription(): bool
    {
        return
            $this->pool instanceof Pool &&
            $this->driver instanceof Driver &&
            $this->car instanceof Car;
    }

    /**
     * @return bool
     */
    public function isValidButMissing(): bool
    {
        return
            $this->isValidInscription() &&
            $this->isMissing();
    }

    /**
     * @return bool
     */
    public function isValidButDisconnected(): bool
    {
        return
            $this->isValidInscription() &&
            $this->isDisconnected() &&
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

    /**
     * @ORM\PrePersist()
     */
    public function setStartPositionOnPersist(): void
    {
        $startPosition = 0;
        foreach ($this->race->getDriverRaces() as $driverRace) {
            if ($this->pool instanceof Pool && $driverRace->hasBeenValidated() && $driverRace->getPool() instanceof Pool && $driverRace->getPool()->getId() === $this->pool->getId()) {
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
    public function validateInscription(): self
    {
        $this->inscriptionStatus = self::VALIDATED;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValidationToken(): ?string
    {
        return $this->validationToken;
    }

    /**
     * @param string|null $validationToken
     * @return $this
     */
    public function setValidationToken(?string $validationToken): self
    {
        $this->validationToken = $validationToken;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRaceTime(): ?string
    {
        return $this->raceTime;
    }

    /**
     * @param string|null $raceTime
     * @return DriverRace
     */
    public function setRaceTime(?string $raceTime): DriverRace
    {
        $this->raceTime = $raceTime;
        $this->raceTimeMilli = Tool::timeToMilli($raceTime);
        $this->updateTotalTime();
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRaceTimeMilli(): ?int
    {
        return $this->raceTimeMilli;
    }

    /**
     * @return int|null
     */
    public function getTotalTimeMilli(): ?int
    {
        return $this->totalTimeMilli;
    }

    /**
     * @return int|null
     */
    public function getBestLapMilli(): ?int
    {
        return $this->bestLapMilli;
    }

    /**
     * @return int|null
     */
    public function getBonusMilli(): ?int
    {
        return $this->bonusMilli;
    }

    /**
     * @return int|null
     */
    public function getPenaltyMilli(): ?int
    {
        return $this->penaltyMilli;
    }

    /**
     * @param int|null $raceTimeMilli
     * @return $this
     */
    public function setRaceTimeMilli(?int $raceTimeMilli): self
    {
        $this->raceTimeMilli = $raceTimeMilli;
        $this->raceTime = Tool::milliToTime($raceTimeMilli);
        $this->updateTotalTime();
        return $this;
    }

    /**
     * @param int|null $totalTimeMilli
     * @return $this
     */
    protected function setTotalTimeMilli(?int $totalTimeMilli): self
    {
        $this->totalTimeMilli = $totalTimeMilli;
        $this->totalTime = Tool::milliToTime($totalTimeMilli);
        return $this;
    }

    /**
     * @param int|null $bestLapMilli
     * @return $this
     */
    public function setBestLapMilli(?int $bestLapMilli): self
    {
        $this->bestLapMilli = $bestLapMilli;
        $this->bestLap = Tool::milliToTime($bestLapMilli);
        return $this;
    }

    /**
     * @param int|null $bonusMilli
     * @return $this
     */
    public function setBonusMilli(?int $bonusMilli): self
    {
        $this->bonusMilli = $bonusMilli;
        $this->bonus = Tool::milliToTime($bonusMilli);
        $this->updateTotalTime();
        return $this;
    }

    /**
     * @param int|null $penaltyMilli
     * @return $this
     */
    public function setPenaltyMilli(?int $penaltyMilli): self
    {
        $this->penaltyMilli = $penaltyMilli;
        $this->penalty = Tool::milliToTime($penaltyMilli);
        $this->updateTotalTime();
        return $this;
    }

    /**
     *
     */
    protected function updateTotalTime():void
    {
        $this->setTotalTimeMilli($this->raceTimeMilli+$this->penaltyMilli-$this->bonusMilli);
    }

}
