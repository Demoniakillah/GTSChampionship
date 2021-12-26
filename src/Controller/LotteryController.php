<?php


namespace App\Controller;


use App\Repository\CarCategoryRepository;
use App\Repository\CountryRepository;
use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LotteryController extends AbstractController
{
    /**
     * @Route("/public/lottery/prepare", name="public_race_combo_lottery_prepare", methods={"GET"})
     * @param CarCategoryRepository $categoryRepository
     * @param CountryRepository $countryRepository
     * @return Response
     */
    public function prepareLottery(CarCategoryRepository $categoryRepository, CountryRepository $countryRepository): Response
    {
        return $this->render('admin/lottery_index.html.twig', [
            'countries' => $countryRepository->createQueryBuilder('countries')->select('countries')->innerJoin('countries.tracks', 'track')->getQuery()->getResult(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/public/lottery/b/{base64Data}", name="public_race_combo_lottery_b", methods={"GET"})
     * @param CarCategoryRepository $categoryRepository
     * @param CountryRepository $countryRepository
     * @param null|string $base64Data
     * @return Response
     */
    public function lotteryBase64(TrackRepository $trackRepository, CarCategoryRepository $categoryRepository, CountryRepository $countryRepository, $base64Data = null): Response
    {
        $data = json_decode(base64_decode($base64Data), true);
        $trackFilter = array_map('current', $trackRepository->createQueryBuilder('track')->select('track.name')->where('track.id in (:idList)')->setParameter('idList', $data['tracks'])->getQuery()->getScalarResult());
        return $this->render('lottery/index.html.twig', [
            'nbRaces' => $data['nbRaces'],
            'countries' => $countryRepository->createQueryBuilder('countries')->select('countries')->innerJoin('countries.tracks', 'track')->where('track.id in (:track)')->setParameter('track', $data['tracks'])->getQuery()->getResult(),
            'categories' => $categoryRepository->createQueryBuilder('categories')->select('categories')->innerJoin('categories.cars', 'car')->where('categories.id in (:categories)')->setParameter('categories', $data['categories'])->getQuery()->getResult(),
            'trackFilter' => $trackFilter
        ]);
    }

    /**
     * @Route("/public/lottery/{nbRaces}", name="public_race_combo_lottery", methods={"GET"}, requirements={"nbRaces"="\d+"})
     * @param CarCategoryRepository $categoryRepository
     * @param CountryRepository $countryRepository
     * @param int $nbRaces
     * @return Response
     */
    public function lottery(CarCategoryRepository $categoryRepository, CountryRepository $countryRepository, $nbRaces = 1): Response
    {
        $countryFilter = ["Autodrome Lago Maggiore", "Autodrome Nazionale Monza", "Autodromo De Interlagos", "Brands Hatch", "Circuit de Barcelona-Catalunya", "Circuit de la Sarthe", "Circuit de Spa-Francorchamps", "Dragon Tail", "Mount Panorama Motor Racing Circuit", "Red Bull Ring", "Sardegna", "Suzuka"];
        $categoryFilter = ["Gr.1", "Gr.2", "Gr.3", "Gr.B", "Gr.X", "N 500", "N 600", "N 700", "N 800", "N1000"];
        return $this->render('lottery/index.html.twig', [
            'nbRaces' => $nbRaces,
            'categories' => $categoryRepository->createQueryBuilder('categories')->select('categories')->innerJoin('categories.cars', 'car')->where('categories.name in (:categories)')->setParameter('categories', $categoryFilter)->getQuery()->getResult(),
            'countries' => $countryRepository->createQueryBuilder('countries')->select('countries')->innerJoin('countries.tracks', 'track')->where('countries.name in (:countries)')->setParameter('countries', $countryFilter)->getQuery()->getResult(),
            'trackFilter' => ["GP", "Autodrome Nazionale Monza", "Autodromo De Interlagos", "Brands Hatch Grand Prix Circuit", "Circuit de Barcelona-Catalunya", "Circuit de la Sarthe", "Circuit de Spa-Francorchamps", "Dragon Trail - Gardens", "Mount Panorama Motor Racing Circuit", "Red Bull Ring", "Windmills", "Suzuka Circuit"]
        ]);
    }
}