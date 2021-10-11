<?php

namespace App\Entity;

use App\Repository\CarRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 * @ORM\Table(uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="maker_cars_idx",
 *              columns={"maker","name"}
 *          )
 *     })
 * @ORM\HasLifecycleCallbacks()
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Maker", inversedBy="cars")
     * @ORM\JoinColumn(name="maker", referencedColumnName="id")
     */
    private ?Maker $maker = null;

    /**
     * @var CarCategory|null
     * @ORM\ManyToOne(targetEntity="App\Entity\CarCategory", inversedBy="cars")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true)
     */
    private ?CarCategory $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = '';

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
    private ?string $power = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $torque = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $weight = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $transmission = '';

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
        return $this->maker->getName() . ' ' . $this->name . ' - ' . $this->category;
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
    public function getRaces()
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

    /**
     * @return string|null
     */
    public function getPower(): ?string
    {
        return $this->power;
    }

    /**
     * @param string|null $power
     * @return $this
     */
    public function setPower(?string $power): self
    {
        $this->power = $power;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTorque(): ?string
    {
        return $this->torque;
    }

    /**
     * @param string|null $torque
     * @return $this
     */
    public function setTorque(?string $torque): self
    {
        $this->torque = $torque;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWeight(): ?string
    {
        return $this->weight;
    }

    /**
     * @param string|null $weight
     * @return $this
     */
    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    /**
     * @param string|null $transmission
     * @return $this
     */
    public function setTransmission(?string $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }
}
