<?php

namespace App\Entity;

use App\Repository\PoolRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=PoolRepository::class)
 * @ORM\Table(uniqueConstraints={
 *     @UniqueConstraint(
 *              name="unique_race_name_by_account",
 *              columns={"user_group","name"}
 *          )
 *     })
 * @ORM\HasLifecycleCallbacks()
 */
class Pool
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
    private ?string $name = '';

    /**
     * @ORM\Column(name="points", type="json")
     */
    private ?array $points = [];

    /**
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private ?int $priority = 0;

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
     * @return int
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return Pool
     */
    public function setPriority(int $priority): Pool
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @var Driver[]
     * @ORM\OneToMany(targetEntity="App\Entity\Driver", mappedBy="pool")
     */
    private $drivers;

    /**
     * @return string
     */
    public function __toString():string
    {
        return $this->name;
    }

    /**
     * Pool constructor.
     */
    public function __construct()
    {
        $this->drivers = new ArrayCollection();
    }

    /**
     * @return Driver[]
     */
    public function getDrivers()
    {
        return $this->drivers;
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
     * @return Pool
     */
    public function setUserGroup(UserGroup $userGroup): Pool
    {
        $this->userGroup = $userGroup;
        return $this;
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
    public function getName(): ?string
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
     * @return array
     */
    public function getPoints():?array
    {
        return $this->points;
    }

    /**
     * @param array $points
     * @return Pool
     */
    public function setPoints(array $points): Pool
    {
        $this->points = $points;

        return $this;
    }
}
