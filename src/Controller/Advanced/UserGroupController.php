<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\UserGroup;
use App\Form\UserGroupType;
use App\Repository\UserGroupRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/user/group")
 */
class UserGroupController extends MainController
{
    /**
     * @Route("/", name="user_group_index", methods={"GET"})
     * @param UserGroupRepository $userGroupRepository
     * @return Response
     */
    public function index(UserGroupRepository $userGroupRepository): Response
    {
        return $this->render('user_group/index.html.twig', [
            'user_groups' => $userGroupRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_group_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new UserGroup(), $request);
    }

    /**
     * @Route("/{id}", name="user_group_show", methods={"GET"}, requirements={"id"="\d+"})
     * @param UserGroup $userGroup
     * @return Response
     */
    public function show(UserGroup $userGroup): Response
    {
        return $this->render('user_group/show.html.twig', [
            'user_group' => $userGroup,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_group_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     * @param Request $request
     * @param UserGroup $userGroup
     * @return Response
     */
    public function edit(Request $request, UserGroup $userGroup): Response
    {
        return $this->updateAction($userGroup, $request);
    }


    /**
     * @Route("/{id}", name="user_group_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @param Request $request
     * @param UserGroup $userGroup
     * @return Response
     */
    public function delete(Request $request, UserGroup $userGroup): Response
    {
        return $this->deleteAction($request,$userGroup);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'user_group';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return UserGroupType::class;
    }
}
