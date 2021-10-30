<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AutoUpdateDriverTimesListener
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * @var TokenStorageInterface
     */
    protected TokenStorageInterface $security;

    /**
     * @param ResponseEvent $event
     * @return AutoUpdateDriverTimesListener
     */
    public function sortDrivers(ResponseEvent $event): self
    {
        if (!$this->security->getToken() instanceof TokenInterface) {
            return $this;
        }
        if (!$this->security->getToken()->getUser() instanceof UserInterface) {
            return $this;
        }
    }

    /**
     * @param EntityManagerInterface $em
     * @return AutoUpdateDriverTimesListener
     */
    public function setEm(EntityManagerInterface $em): self
    {
        $this->em = $em;
        return $this;
    }

    /**
     * @param  $security
     * @return AutoUpdateDriverTimesListener
     */
    public function setSecurity(TokenStorageInterface $security): self
    {
        $this->security = $security;
        return $this;
    }
}