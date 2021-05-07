<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 */
class Driver
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $psn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="id")
     * @ORM\JoinColumn(name="team", referencedColumnName="id")
     */
    private $team;

    /**
     * @var Pool
     * @ORM\ManyToOne(targetEntity="App\Entity\Pool", inversedBy="drivers")
     * @ORM\JoinColumn(name="pool",referencedColumnName="id")
     */
    private $pool;

    /**
     * @var DriverRace[]
     * @ORM\OneToMany(targetEntity="App\Entity\DriverRace", mappedBy="driver")
     */
    private $races;

    public function __construct()
    {
        $this->races = new ArrayCollection();
    }

    /**
     * @return DriverRace[]
     */
    public function getRaces(): array
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
    public function getPool(): Pool
    {
        return $this->pool;
    }

    /**
     * @param Pool $pool
     */
    public function setPool(Pool $pool): self
    {
        $this->pool = $pool;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPsn(): ?string
    {
        return $this->psn;
    }

    public function setPsn(string $psn): self
    {
        $this->psn = $psn;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
