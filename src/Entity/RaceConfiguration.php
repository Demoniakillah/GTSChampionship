<?php

namespace App\Entity;

use App\Repository\RaceConfigurationRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RaceConfigurationRepository::class)
 * @ORM\Table(uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="race_parameter_idx",
 *              columns={"parameter","race"}
 *          )
 *     })
 * @ORM\HasLifecycleCallbacks()
 */
class RaceConfiguration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="configurations")
     * @ORM\JoinColumn(name="race", referencedColumnName="id")
     */
    private ?Race $race = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceParameter"))
     * @ORM\JoinColumn(name="parameter", referencedColumnName="id")
     */
    private ?RaceParameter $parameter = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $value = '';

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
