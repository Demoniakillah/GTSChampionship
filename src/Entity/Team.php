<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 * @ORM\Table(uniqueConstraints={
 *     @UniqueConstraint(
 *              name="unique_race_name_by_account",
 *              columns={"user_group","name"}
 *          )
 *     })
 */
class Team
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
     * @var Driver[]
     * @ORM\OneToMany(targetEntity="App\Entity\Driver", mappedBy="team")
     */
    private $drivers;

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
        return $this->name;
    }

    /**
     * Team constructor.
     */
    public function __construct()
    {
        $this->drivers = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return Team
     */
    public function setUserGroup(UserGroup $userGroup): Team
    {
        $this->userGroup = $userGroup;
        return $this;
    }

    /**
     * @return Driver[]
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * @param Driver $driver
     * @return Team
     */
    public function addDriver(Driver $driver): Team
    {
        $this->drivers = $driver;

        return $this;
    }

    /**
     * @param Driver[] $drivers
     * @return Team
     */
    public function setDrivers(array $drivers): Team
    {
        $this->drivers = $drivers;

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
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
