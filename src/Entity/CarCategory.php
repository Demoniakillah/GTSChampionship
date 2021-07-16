<?php

namespace App\Entity;

use App\Repository\CarCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass=CarCategoryRepository::class)
 */
class CarCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @var Car[]|PersistentCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Car", mappedBy="category")
     */
    protected $cars;

    /**
     * @return string
     */
    public function __toString():string
    {
        return $this->name;
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
     * @return Car[]|PersistentCollection
     */
    public function getCars()
    {
        return $this->cars;
    }

    /**
     * @param Car[]|PersistentCollection $cars
     * @return CarCategory
     */
    public function setCars($cars):self
    {
        $this->cars = $cars;
        return $this;
    }
}
