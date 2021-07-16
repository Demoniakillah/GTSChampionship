<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 * @ORM\Table(uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="maker_cars_idx",
 *              columns={"maker","name"}
 *          )
 *     }) */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @var Maker
     * @ORM\ManyToOne(targetEntity="App\Entity\Maker", inversedBy="cars")
     * @ORM\JoinColumn(name="maker", referencedColumnName="id")
     */
    private Maker $maker;

    /**
     * @var CarCategory|null
     * @ORM\ManyToOne(targetEntity="App\Entity\CarCategory", inversedBy="cars")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true)
     */
    private ?CarCategory $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @var DriverRace[]
     * @ORM\OneToMany(targetEntity="App\Entity\DriverRace", mappedBy="car")
     */
    private $races;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $imageSrc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $power;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $torque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transmission;

    /**
     * @return string
     */
    public function __toString():string
    {
        return $this->maker->getName() . ' ' . $this->name;
    }

    /**
     * Car constructor.
     */
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
     * @return string|null
     */
    public function getImageSrc(): ?string
    {
        return $this->imageSrc;
    }

    /**
     * @param string|null $imageSrc
     * @return $this
     */
    public function setImageSrc(?string $imageSrc): self
    {
        $this->imageSrc = $imageSrc;

        return $this;
    }

    /**
     * @return CarCategory
     */
    public function getCategory(): ?CarCategory
    {
        return $this->category;
    }

    /**
     * @param CarCategory $category
     * @return Car
     */
    public function setCategory(CarCategory $category): Car
    {
        $this->category = $category;
        return $this;
    }

    public function getPower(): ?string
    {
        return $this->power;
    }

    public function setPower(?string $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getTorque(): ?string
    {
        return $this->torque;
    }

    public function setTorque(?string $torque): self
    {
        $this->torque = $torque;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(?string $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }
}
