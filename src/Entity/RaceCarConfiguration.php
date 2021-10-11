<?php

namespace App\Entity;

use App\Repository\RaceCarConfigurationRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RaceCarConfigurationRepository::class)
 * @ORM\Table(uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="race_car_parameter_idx",
 *              columns={"race","car","parameter"}
 *          )
 *     })
 * @ORM\HasLifecycleCallbacks()
 */
class RaceCarConfiguration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="carConfigurations")
     * @ORM\JoinColumn(name="race", referencedColumnName="id")
     */
    private ?Race $race = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car")
     * @ORM\JoinColumn(name="car", referencedColumnName="id")
     */
    private ?Car $car = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceCarParameter")
     * @ORM\JoinColumn(name="parameter", referencedColumnName="id")
     */
    private ?RaceCarParameter $parameter = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $value = 0;

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
    public function getId(): ?int
    {
        return $this->id;
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
     * @return $this
     */
    public function setRace(Race $race): self
    {
        $this->race = $race;

        return $this;
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
     * @return $this
     */
    public function setCar(Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return RaceCarParameter
     */
    public function getParameter():?RaceCarParameter
    {
        return $this->parameter;
    }

    /**
     * @param $parameter RaceCarParameter
     * @return $this
     */
    public function setParameter(RaceCarParameter $parameter): self
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
