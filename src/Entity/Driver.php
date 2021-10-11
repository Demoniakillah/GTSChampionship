<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 * @ORM\Table(uniqueConstraints={
 *     @UniqueConstraint(
 *              name="unique_race_name_by_account",
 *              columns={"user_group","psn"}
 *          )
 *     })
 * @ORM\HasLifecycleCallbacks()
 */
class Driver
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $psn = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="drivers")
     * @ORM\JoinColumn(name="team", referencedColumnName="id", onDelete="SET NULL")
     */
    private ?Team $team = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pool", inversedBy="drivers")
     * @ORM\JoinColumn(name="pool",referencedColumnName="id", onDelete="SET NULL")
     */
    private ?Pool $pool = null;

    /**
     * @var DriverRace[]
     * @ORM\OneToMany(targetEntity="App\Entity\DriverRace", mappedBy="driver", cascade={"remove"})
     */
    private $races;

    /**
     * @var UserGroup
     * @ORM\ManyToOne(targetEntity="App\Entity\UserGroup", inversedBy="races")
     * @ORM\JoinColumn(name="user_group", referencedColumnName="id")
     */
    private UserGroup $userGroup;

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
     **/
    public function setCreationDateOnPrePersist(): self
    {
        $this->creationDate = new DateTime;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @return $this
     **/
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
    public function __toString():string
    {
        return $this->psn;
    }

    /**
     * @return UserGroup
     */
    public function getUserGroup(): UserGroup
    {
        return $this->userGroup;
    }

    /**
     * @param UserGroup $userGroup
     * @return Driver
     */
    public function setUserGroup(UserGroup $userGroup): Driver
    {
        $this->userGroup = $userGroup;
        return $this;
    }

    /**
     * Driver constructor.
     */
    public function __construct()
    {
        $this->races = new ArrayCollection();
    }

    /**
     * @return DriverRace[]
     */
    public function getRaces()
    {
        return $this->races;
    }

    /**
     * @param DriverRace[] $races
     * @return Driver
     */
    public function setRaces(array $races): Driver
    {
        $this->races = $races;

        return $this;
    }

    /**
     * @param DriverRace $race
     * @return Driver
     */
    public function addRace(DriverRace $race): Driver
    {
        $this->races = $race;

        return $this;
    }

    /**
     * @return Pool
     */
    public function getPool(): ?Pool
    {
        return $this->pool;
    }

    /**
     * @param Pool|null $pool
     * @return Driver
     */
    public function setPool(?Pool $pool): self
    {
        $this->pool = $pool;

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
     * @return string
     */
    public function getPsn(): string
    {
        return $this->psn;
    }

    /**
     * @param string $psn
     * @return $this
     */
    public function setPsn(string $psn): self
    {
        $this->psn = $psn;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Team|null
     */
    public function getTeam(): ?Team
    {
        return $this->team;
    }

    /**
     * @param Team|null $team
     * @return $this
     */
    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
