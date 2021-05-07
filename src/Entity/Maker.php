<?php

namespace App\Entity;

use App\Repository\MakerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MakerRepository::class)
 */
class Maker
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
    private $name;

    /**
     * @var Car[]
     * @ORM\OneToMany(targetEntity="App\Entity\Car", mappedBy="maker")
     */
    private $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    /**
     * @param Car[] $cars
     */
    public function setCars(array $cars): self
    {
        $this->cars = $cars;

        return $this;
    }

    /**
     * @return Car[]
     */
    public function getCars(): array
    {
        return $this->cars;
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
