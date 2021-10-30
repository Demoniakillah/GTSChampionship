<?php


namespace App\Controller;

use App\Entity\S4CarList;
use App\Entity\S4CarSelect;
use App\Repository\S4CarListRepository;
use App\Repository\S4CarSelectRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class S4CarSelectController extends AbstractController
{
    /**
     * @Route("/public/echo_gts/s4/car_select/{teamName}", methods={"GET"})
     * @param string $teamName
     * @param S4CarListRepository $s4CarListRepository
     * @param S4CarSelectRepository $s4CarSelectRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(string $teamName, S4CarListRepository $s4CarListRepository, S4CarSelectRepository $s4CarSelectRepository): Response
    {
        $team = $s4CarSelectRepository->createQueryBuilder('ss')->where('ss.teamName = :teamName')->andWhere('ss.car is null')->setParameter('teamName', $teamName)->getQuery()->getOneOrNullResult();
        if (!$team instanceof S4CarSelect) {
            $team = $s4CarSelectRepository->createQueryBuilder('ss')->where('ss.teamName = :teamName')->andWhere('ss.car is not null')->setParameter('teamName', $teamName)->getQuery()->getOneOrNullResult();
            if ($team instanceof S4CarSelect) {
                return $this->render('s4_car_select/already_selected.html.twig', ['team' => $team]);
            }
            return $this->render('s4_car_select/error.html.twig', ['team' => $team]);
        }

        $previousTeam = $s4CarSelectRepository->createQueryBuilder('ss')->where('ss.car is null')->andWhere('ss.teamPosition < :prevPosition')->setParameter('prevPosition', $team->getTeamPosition() - 1)->setMaxResults(1)->getQuery()->getOneOrNullResult();

        if ($previousTeam instanceof S4CarSelect) {
            return $this->render('s4_car_select/try_later.html.twig',['previous'=>$previousTeam, 'team'=>$team]);
        }

        $team->setToken(md5(sha1(uniqid('',true))));
        $this->getDoctrine()->getManager()->flush();
        return $this->render('s4_car_select/index.html.twig', [
            'cars' => $s4CarListRepository->findBy([], ['name' => 'asc']),
            'team' => $team
        ]);
    }

    /**
     * @param Request $request
     * @param S4CarSelectRepository $carSelectRepository
     * @param S4CarListRepository $s4CarListRepository
     * @return Response
     * @Route("/public/echo_gts/s4/car_select/submit"), methods={"POST"})
     */
    public function insert(Request $request, S4CarSelectRepository $carSelectRepository, S4CarListRepository $s4CarListRepository):Response
    {
        $team = $carSelectRepository->find($request->request->get('team'));
        $car = $s4CarListRepository->find($request->request->get('car_selected'));

        if(!$car instanceof S4CarList || !$team instanceof S4CarSelect || $request->request->get('token') !== $team->getToken()){
            return $this->render('s4_car_select/error.html.twig',['team'=>$team]);
        }
        $team->setCar($car->getName());
        $this->getDoctrine()->getManager()->remove($car);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('s4_car_select/thanks.html.twig',['team'=>$team]);
    }
}

/*
 * SCRIPT
 *
 * mysql -hlocalhost -P3301 -pSymfony-8000 -usf_mysql GTS -e'truncate table s4_car_select';  mysql -hlocalhost -P3301 -pSymfony-8000 -usf_mysql GTS -e'truncate table s4_car_list';mysql -hlocalhost -P3301 -pSymfony-8000 -usf_mysql GTS -e'insert into s4_car_list values (null,"ALFA ROMEO 4C GR.3"),(null,"ASTON MARTIN DBR9 GT1 2010"),(null,"ASTON MARTIN VI2 VANTAGE GT3 2012"),(null,"AUDI R8 LMS AUDI SPORT TEAM WRT 2015"),(null,"BMW BMW M6 GT3 M POWER LIVERY 2016"),(null,"BMW BMW M6 GT3 WALKENHORST MOTORSPORT 2016"),(null,"BMW BMW Z4 GT3 2011"),(null,"BMW M3 GT BMW Motorsport 2011"),(null,"CHEVROLET CORVETTE C7 GR.3"),(null,"CITROEN GT BY CITROEN RACE CAR GR.3"),(null,"DODGE VIPER SRT GT3-R 2015"),(null,"FERRARI 458 ITALIA GT3 2013"),(null,"FORD FORD GT LM SPEC II TEST CAR"),(null,"FORD MUSTANG GR.3"),(null,"HONDA NSX GR.3"),(null,"HYUNDAI GENESIS GR.3"),(null,"JAGUAR F-TYPE GR.3"),(null,"LAMBORGHINI HURACAN GT3 2015"),(null,"LEXUS RC F GT3 EMIL FREY RACING 2017"),(null,"LEXUS RCF GT3 PROTOTYPE EMIL FREY RACING 2016"),(null,"MAZDA ATENZA GR.3"),(null,"MCLAREN 650S GT3 2015"),(null,"MCLAREN F1 GTR - BMW (Kokusai Kaihatsu UK Racing) 1995"),(null,"MERCEDES-BENZ MERCEDES-AMG GT3 AMG-TEAM HTP-MOTORSPORT 2016"),(null,"MERCEDES-BENZ SLS AMG GT3 2011"),(null,"MITSUBISHI LANCER EVOLUTION FINAL EDITION GR.3"),(null,"NISSAN GT-R NISMO GT3 N24 SCHULZE MOTORSPORT 2013"),(null,"PEUGEOT RCZ GR.3"),(null,"PORSCHE 911 RSR 2017"),(null,"RENAULT SPORT R.S.01 GT3 2016"),(null,"SUBARU WRX GR.3"),(null,"TOYOTA 2018 GR Supra Racing Concept 2018"),(null, "VOLKSWAGEN BEETLE GR.3");' ; nano /tmp/teams && while IFS= read -r line; do pos=$(echo $line | awk -F'\t' '{print $1}'); team=$(echo $line | awk -F'\t' '{print $2}'); mysql -hlocalhost -P3301 -pSymfony-8000 -usf_mysql GTS -e"insert into s4_car_select values (null,'$team',$pos,null,null);"; done < /tmp/teams
 */