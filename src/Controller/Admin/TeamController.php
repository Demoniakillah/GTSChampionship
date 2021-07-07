<?php


namespace App\Controller\Admin;


use App\Entity\Driver;
use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Advanced\TeamController as BaseController;

/**
 * TeamController
 * TeamController.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 13/05/2021
 * @Route("/admin/team")
 */
class TeamController extends BaseController
{
    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'admin_team';
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="admin_team_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $team = new Team();
        $team->setUserGroup($this->getUser()->getUserGroup());
        return $this->updateAction($team, $request, true, true);
    }

    /**
     * @param Request $request
     * @param Team $team
     * @return Response
     * @Route("/{id}/edit", name="admin_team_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Team $team): Response
    {
        return $this->updateAction($team, $request, false, true);
    }

    /**
     * @Route("/team/updatedriver",methods={"POST"}, name="api_update_driver_team")
     */
    public function updateDriverTeam(Request $request): JsonResponse
    {
        $driver = $this->getDoctrine()->getRepository(Driver::class)->find($request->request->get('driver'));
        if ($driver instanceof Driver) {
            if ((int)$request->request->get('team') === 0) {
                $driver->setTeam(null);
            } else {
                $team = $this->getDoctrine()->getRepository(Team::class)->find($request->request->get('team'));
                if ($team instanceof Team) {
                    $driver->setTeam($team);
                }
            }
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->json(true);
    }

    /**
     * @Route("/index",methods={"GET"}, name="team_admin_index")
     */
    public function index(TeamRepository $teamRepository): Response
    {
        $drivers = $this->getDoctrine()->getRepository(Driver::class)->findBy(['userGroup' => $this->getUser()->getUserGroup()], ['psn' => 'asc']);
        $out = [0 => ['drivers' => []]];
        foreach ($teamRepository->findBy(['userGroup' => $this->getUser()->getUserGroup()], ['name' => 'asc']) as $team) {
            $out[$team->getId()] = ['name' => (string)$team, 'drivers' => []];
        }
        foreach ($drivers as $driver) {
            if ($driver->getTeam() instanceof Team) {
                $out[$driver->getTeam()->getId()]['drivers'][] = $driver;
            } else {
                $out[0]['drivers'][] = $driver;
            }
        }

        return $this->render(
            'admin/team_index.html.twig',
            [
                'teams' => $out
            ]
        );
    }

}