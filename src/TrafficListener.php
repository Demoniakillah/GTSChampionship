<?php


namespace App;


use App\Entity\Traffic;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class TrafficListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param TerminateEvent $event
     */
    public function onKernelTerminate(TerminateEvent $event): void
    {
//        if(preg_match('#(^/(_(profiler|wdt)|css|images|js)/)|((\.(js|css|jpg|jpeg|png|gif))$)#',$event->getRequest()->getPathInfo())){
//            return;
//        }
//        try {
//            $traffic = (new Traffic())
//                ->setDate(new \DateTime)
//                ->setUri($event->getRequest()->getPathInfo())
//                ->setMethod($event->getRequest()->getMethod())
//                ->setRequestBody($event->getRequest()->getContent())
//                ->setRequestHeaders(json_encode($event->getRequest()->headers->all()))
//                ->setResponseBody($event->getResponse()->getContent())
//                ->setResponseHeaders(json_encode($event->getResponse()->headers->all()))
//                ->setResponseStatusCode($event->getResponse()->getStatusCode());
//            $this->em->persist($traffic);
//            $this->em->flush();
//        } catch (\Exception $e) {
//            $this->logger->error($e->getMessage());
//        }
    }

    /**
     * @param EntityManagerInterface $em
     * @return $this
     */
    public function setEm(EntityManagerInterface $em): TrafficListener
    {
        $this->em = $em;
        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return TrafficListener
     */
    public function setLogger(LoggerInterface $logger): TrafficListener
    {
        $this->logger = $logger;
        return $this;
    }

}