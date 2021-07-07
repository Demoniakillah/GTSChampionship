<?php

namespace App\Entity;

use App\Repository\TeamRepository;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

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

    public function __toString():string
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->drivers = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
