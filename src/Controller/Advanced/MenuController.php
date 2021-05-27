<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/menu")
 */
class MenuController extends MainController
{
    /**
     * @Route("/", name="menu_index", methods={"GET"})
     */
    public function index(MenuRepository $menuRepository): Response
    {
        return $this->render(
            'menu/index.html.twig',
            [
                'menus' => $menuRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="menu_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Menu(), $request);
    }

    /**
     * @Route("/{id}", name="menu_show", methods={"GET"})
     */
    public function show(Menu $menu): Response
    {
        return $this->render(
            'menu/show.html.twig',
            [
                'menu' => $menu,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="menu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Menu $menu): Response
    {
        return $this->updateAction($menu, $request, false);
    }

    /**
     * @Route("/{id}", name="menu_delete", methods={"POST"})
     */
    public function delete(Request $request, Menu $menu): Response
    {
        return $this->deleteAction($request, $menu);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'menu';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return MenuType::class;
    }
}
