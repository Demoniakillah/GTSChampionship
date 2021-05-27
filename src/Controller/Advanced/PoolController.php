<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Pool;
use App\Entity\PoolConfiguration;
use App\Form\PoolType;
use App\Repository\PoolRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/pool")
 */
class PoolController extends MainController
{
    /**
     * @Route("/", name="pool_index", methods={"GET"})
     */
    public function index(PoolRepository $poolRepository): Response
    {
        return $this->render(
            'pool/index.html.twig',
            [
                'pools' => $poolRepository->findBy([], ['priority' => 'asc']),
            ]
        );
    }

    /**
     * @return array
     */
    protected function getFormOptions(): array
    {
        return array_merge(
            parent::getFormOptions(),
            [
                'pool_repository' => $this->getDoctrine()->getRepository(Pool::class),
                'pool_configuration_repository' => $this->getDoctrine()->getRepository(PoolConfiguration::class),
            ]
        );
    }

    /**
     * @Route("/new", name="pool_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Pool(), $request);
    }

    /**
     * @param $request
     * @param $entity
     * @param $form
     */
    protected function submit($request, $entity, $form): void
    {
        $points = [];
        foreach ($request->request->get('pool') as $name => $value) {
            if (0 === strpos($name, "points_")) {
                $index = (int)str_replace('points_', '', $name);
                $points[$index] = (int)$value;
            }
        }
        ksort($points);
        $form->getData()->setPoints($points);
        parent::submit($request, $entity, $form);
    }

    /**
     * @Route("/{id}", name="pool_show", methods={"GET"})
     */
    public function show(Pool $pool): Response
    {
        return $this->render(
            'pool/show.html.twig',
            [
                'pool' => $pool,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="pool_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pool $pool): Response
    {
        return $this->updateAction($pool, $request, false);
    }

    /**
     * @Route("/{id}", name="pool_delete", methods={"POST"})
     */
    public function delete(Request $request, Pool $pool): Response
    {
        return $this->deleteAction($request, $pool);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'pool';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return PoolType::class;
    }
}
