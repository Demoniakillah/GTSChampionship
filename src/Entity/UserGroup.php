<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class UserGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $name = '';

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private ?string $imageUrl = '';

    /**
     * @var PersistentCollection|User[]
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="userGroup", cascade={"remove"})
     */
    private $accounts;

    /**
     * @var PersistentCollection|Race[]
     * @ORM\OneToMany(targetEntity="App\Entity\Race", mappedBy="userGroup", cascade={"remove"})
     */
    private $races;

    /**
     * @var PersistentCollection|Driver[]
     * @ORM\OneToMany(targetEntity="App\Entity\Driver", mappedBy="userGroup", cascade={"remove"})
     */
    private $drivers;

    /**
     * @var PersistentCollection|team[]
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="userGroup", cascade={"remove"})
     */
    private $teams;

    /**
     * @var PersistentCollection|Pool[]
     * @ORM\OneToMany(targetEntity="App\Entity\Pool", mappedBy="userGroup", cascade={"remove"})
     */
    private $pools;

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
     * @return string|null
     */
    public function __toString():string
    {
        return $this->name;
    }

    /**
     * UserGroup constructor.
     */
    public function __construct()
    {
        $this->accounts = new ArrayCollection();
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
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl
     * @return UserGroup
     */
    public function setImageUrl(?string $imageUrl): UserGroup
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return User[]|PersistentCollection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param User[]|PersistentCollection $accounts
     * @return UserGroup
     */
    public function setAccounts($accounts):self
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * @return Race[]|PersistentCollection
     */
    public function getRaces()
    {
        return $this->races;
    }

    /**
     * @param Race[]|PersistentCollection $races
     * @return UserGroup
     */
    public function setRaces($races):self
    {
        $this->races = $races;
        return $this;
    }

    /**
     * @return Driver[]|PersistentCollection
     */
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * @param Driver[]|PersistentCollection $drivers
     * @return UserGroup
     */
    public function setDrivers($drivers):self
    {
        $this->drivers = $drivers;
        return $this;
    }

    /**
     * @return team[]|PersistentCollection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param team[]|PersistentCollection $teams
     * @return UserGroup
     */
    public function setTeams($teams):self
    {
        $this->teams = $teams;
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
     * @param string|null $name
     * @return UserGroup
     */
    public function setName(?string $name): UserGroup
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Pool[]|PersistentCollection
     */
    public function getPools()
    {
        return $this->pools;
    }

    /**
     * @param Pool[]|PersistentCollection $pools
     * @return UserGroup
     */
    public function setPools($pools):self
    {
        $this->pools = $pools;
        return $this;
    }
}
