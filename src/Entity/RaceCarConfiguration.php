<?php

namespace App\Entity;

use App\Repository\RaceCarConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RaceCarConfigurationRepository::class)
 * @ORM\Table(uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="race_car_parameter_idx",
 *              columns={"race","car","parameter"}
 *          )
 *     }) */
class RaceCarConfiguration
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var Race
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="carConfigurations")
     * @ORM\JoinColumn(name="race", referencedColumnName="id")
     */
    private Race $race;

    /**
     * @var Car
     * @ORM\ManyToOne(targetEntity="App\Entity\Car")
     * @ORM\JoinColumn(name="car", referencedColumnName="id")
     */
    private Car $car;

    /**
     * @var RaceCarParameter
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceCarParameter")
     * @ORM\JoinColumn(name="parameter", referencedColumnName="id")
     */
    private RaceCarParameter $parameter;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private int $value = 0;

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
