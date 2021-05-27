<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var Track[]
     * @ORM\OneToMany(targetEntity="App\Entity\Track", mappedBy="country")
     */
    private $tracks;

    public function __toString():string
    {
       return $this->name;
    }

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
    }

    /**
     * @param Track[] $tracks
     */
    public function setTracks(array $tracks): self
    {
        $this->tracks = $tracks;

        return $this;
    }

    public function addTrack(Track $track): Country
    {
        $this->tracks[] = $track;

        return $this;
    }

    /**
     * @return Track[]
     */
    public function getTracks(): PersistentCollection
    {
        return $this->tracks;
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