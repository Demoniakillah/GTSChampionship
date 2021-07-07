<?php

namespace App\Entity;

use App\Repository\RaceConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RaceConfigurationRepository::class)
 * @ORM\Table(uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="race_parameter_idx",
 *              columns={"parameter","race"}
 *          )
 *     })
 */
class RaceConfiguration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Race
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="configurations")
     * @ORM\JoinColumn(name="race", referencedColumnName="id")
     */
    private $race;

    /**
     * @var RaceParameter
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceParameter"))
     * @ORM\JoinColumn(name="parameter", referencedColumnName="id")
     */
    private $parameter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Race|null
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
     * @return RaceParameter|null
     */
    public function getParameter(): ?RaceParameter
    {
        return $this->parameter;
    }

    /**
     * @param RaceParameter $parameter
     * @return $this
     */
    public function setParameter(RaceParameter $parameter): self
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
