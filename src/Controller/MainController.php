<?php


namespace App\Controller;

use App\Entity\Menu;
use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * MainController
 * MainController.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 07/05/2021
 */
abstract class MainController extends AbstractController
{
    /**
     * @return User
     */
    protected function getUser():User
    {
        return parent::getUser();
    }

    /**
     * @param Request $request
     * @param $entity
     * @param bool $csrfToken
     * @return Response
     */
    protected function deleteAction(Request $request, $entity, $csrfToken = null): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $csrfToken ?? $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entity);
            $entityManager->flush();
        } else {
            $this->get('logger')->error("csrf token ".$csrfToken ?? $request->request->get('_token')." is not valid for ". 'delete'.$entity->getId());
        }

        return $this->redirectToIndex();
    }


    /**
     * @param $entity
     * @param Request $request
     * @param bool $new
     * @param bool $formOnly
     * @return Response
     */
    protected function updateAction($entity, Request $request, bool $new = true, bool $formOnly = false): Response
    {
        $form = $this->createForm($this->getFormTypeClass(), $entity, $this->getFormOptions());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->submit($request, $entity, $form);

            return $this->redirectToIndex();
        }
        $form = $form->createView();

        if ($formOnly) {
            return $this->render(
                $this->getFormTemplate(),
                [
                    $this->getName() => $entity,
                    'form' => $form,
                    'removeButton' => true,
                    'form_action_url' => $this->generateUrl($this->getName() . ($new?'_new':'_edit'), [
                        'id' => $entity->getId()
                    ])
                ]
            );
        }

        return $this->render(
            ($new === true ? $this->getNewTemplate() : $this->getEditTemplate()),
            [
                $this->getName() => $entity,
                'form' => $form,
            ]
        );
    }

    /**
     * @param Request $request
     * @param $entity
     * @param FormInterface $form
     */
    protected function submit(Request $request, $entity, FormInterface $form): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    /**
     * @return RedirectResponse
     */
    protected function redirectToIndex(): RedirectResponse
    {
        $menuItem = $this->getDoctrine()->getRepository(Menu::class)->findOneBy(
            ['route' => $this->getIndexRoute()]
        );
        if ($menuItem instanceof Menu) {
            return $this->redirect($this->generateUrl('admin_welcome')."#".$menuItem->getId());
        }

        return $this->redirectToRoute('admin_welcome');
    }

    /**
     * @return array
     */
    protected function getFormOptions(): array
    {
        return [];
    }

    /**
     * @return string
     */
    protected function getIndexRoute(): string
    {
        return $this->getName().'_index';
    }

    /**
     * @return string
     */
    protected function getNewTemplate(): string
    {
        return $this->getName().'/new.html.twig';
    }

    /**
     * @return string
     */
    protected function getFormTemplate(): string
    {
        return $this->getName().'/_form.html.twig';
    }

    /**
     * @return string
     */
    protected function getEditTemplate(): string
    {
        return $this->getName().'/edit.html.twig';
    }

    /**
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * @return string
     */
    abstract protected function getFormTypeClass(): string;

}