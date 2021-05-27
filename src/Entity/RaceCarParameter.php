<?php

namespace App\Entity;

use App\Repository\RaceCarParameterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RaceCarParameterRepository::class)
 */
class RaceCarParameter
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $unity;


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
     * @return string
     */
    public function getUnity(): ?string
    {
        return $this->unity;
    }

    /**
     * @param string $unity
     * @return RaceCarParameter
     */
    public function setUnity(string $unity): RaceCarParameter
    {
        $this->unity = $unity;

        return $this;
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
}
