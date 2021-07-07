<?php

namespace App\Entity;

use App\Repository\TermsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TermsRepository::class)
 */
class Terms
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @var UserGroup
     * @ORM\ManyToOne(targetEntity="App\Entity\UserGroup", inversedBy="accounts")
     *
     */
    private UserGroup $userGroup;

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
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @return UserGroup
     */
    public function getUserGroup(): UserGroup
    {
        return $this->userGroup;
    }

    /**
     * @param UserGroup $userGroup
     * @return Terms
     */
    public function setUserGroup(UserGroup $userGroup): Terms
    {
        $this->userGroup = $userGroup;
        return $this;
    }

    /**
     * @param string|null $text
     * @return $this
     */
    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->text;
    }
}
