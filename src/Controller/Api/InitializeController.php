<?php


namespace App\Controller\Api;


use App\Repository\DriverRepository;
use App\Repository\PoolRepository;
use App\Repository\RaceCarConfigurationRepository;
use App\Repository\RaceRepository;
use App\Repository\TeamRepository;
use App\Repository\TermsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class InitializeController extends AbstractController
{
    /**
     * @Route("/initialize", name="initialize", methods={"GET"})
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Unauthorized")
     * @param DriverRepository $driverRepository
     * @param RaceRepository $raceRepository
     * @param TeamRepository $teamRepository
     * @param EntityManagerInterface $entityManager
     * @param TermsRepository $termsRepository
     * @param PoolRepository $poolRepository
     * @return RedirectResponse
     */
    public function initialize(
        DriverRepository $driverRepository,
        RaceRepository $raceRepository,
        TeamRepository $teamRepository,
        EntityManagerInterface $entityManager,
        TermsRepository $termsRepository,
        PoolRepository $poolRepository
    ): RedirectResponse
    {
        foreach ([
                     $raceRepository,
                     $driverRepository,
                     $teamRepository,
                     $poolRepository,
                     $termsRepository
                 ] as $repository) {
            foreach ($repository->findBy(['userGroup'=>$this->getUser()->getUserGroup()]) as $element) {
                $entityManager->remove($element);
            }
        }
        $entityManager->flush();

        return $this->redirect($this->generateUrl('admin_welcome'));
    }
}