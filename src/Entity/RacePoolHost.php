<?php

namespace App\Entity;

use App\Repository\RacePoolHostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RacePoolHostRepository::class)
 */
class RacePoolHost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @var Race
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="poolHosts")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private Race $race;

    /**
     * @var Pool
     * @ORM\ManyToOne(targetEntity="App\Entity\Pool")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private Pool $pool;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return $this
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
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
     * @return RacePoolHost
     */
    public function setRace(Race $race): self
    {
        $this->race = $race;
        return $this;
    }

    /**
     * @return Pool
     */
    public function getPool(): ?Pool
    {
        return $this->pool;
    }

    /**
     * @param Pool $pool
     * @return RacePoolHost
     */
    public function setPool(Pool $pool): self
    {
        $this->pool = $pool;
        return $this;
    }
}
