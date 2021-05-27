<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/team")
 */
class TeamController extends MainController
{
    /**
     * @Route("/", name="team_index", methods={"GET"})
     */
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render(
            'team/index.html.twig',
            [
                'teams' => $teamRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="team_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Team(), $request);
    }

    /**
     * @Route("/{id}", name="team_show", methods={"GET"})
     */
    public function show(Team $team): Response
    {
        return $this->render(
            'team/show.html.twig',
            [
                'team' => $team,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="team_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Team $team): Response
    {
        return $this->updateAction($team, $request, false);
    }

    /**
     * @Route("/{id}", name="team_delete", methods={"POST"})
     */
    public function delete(Request $request, Team $team): Response
    {
        return $this->deleteAction($request, $team);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'team';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return TeamType::class;
    }
}
