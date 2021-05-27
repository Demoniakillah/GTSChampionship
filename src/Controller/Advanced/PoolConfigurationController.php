<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\PoolConfiguration;
use App\Form\PoolConfigurationType;
use App\Repository\MenuRepository;
use App\Repository\PoolConfigurationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/pool/configuration")
 */
class PoolConfigurationController extends MainController
{
    /**
     * @Route("/", name="pool_configuration_index", methods={"GET"})
     */
    public function index(PoolConfigurationRepository $poolConfigurationRepository): Response
    {
        return $this->render('pool_configuration/index.html.twig', [
            'pool_configurations' => $poolConfigurationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pool_configuration_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new PoolConfiguration(),$request,false);
    }

    /**
     * @Route("/{id}", name="pool_configuration_show", methods={"GET"})
     */
    public function show(PoolConfiguration $poolConfiguration): Response
    {
        return $this->render('pool_configuration/show.html.twig', [
            'pool_configuration' => $poolConfiguration,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pool_configuration_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PoolConfiguration $poolConfiguration, MenuRepository $menuRepository): Response
    {
        return $this->updateAction($poolConfiguration,$request,false);
    }

    /**
     * @Route("/{id}", name="pool_configuration_delete", methods={"POST"})
     */
    public function delete(Request $request, PoolConfiguration $poolConfiguration): Response
    {
        return $this->deleteAction($request, $poolConfiguration);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'pool_configuration';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return PoolConfigurationType::class;
    }
}
