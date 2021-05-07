<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Maker
     * @ORM\ManyToOne(targetEntity="App\Entity\Maker", inversedBy="cars")
     * @ORM\JoinColumn(name="maker", referencedColumnName="id")
     */
    private $maker;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var DriverRace[]
     * @ORM\OneToMany(targetEntity="App\Entity\DriverRace", mappedBy="car")
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
     * @param Race $race
     * @return Car
     */
    public function addRace(Race $race): Car
    {
        $this->races = $race;

        return $this;
    }

    /**
     * @param DriverRace[] $races
     * @return Car
     */
    public function setRaces(array $races): Car
    {
        $this->races = $races;

        return $this;
    }

    /**
     * @return Maker
     */
    public function getMaker(): Maker
    {
        return $this->maker;
    }

    /**
     * @param Maker $maker
     */
    public function setMaker(Maker $maker): self
    {
        $this->maker = $maker;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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
