<?php


namespace App\Controller\Admin;


use App\Entity\Driver;
use App\Entity\Pool;
use App\Entity\PoolConfiguration;
use App\Repository\PoolRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Advanced\PoolController as BaseController;

/**
 * PoolController
 * PoolController.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 13/05/2021
 * @Route("/admin/pool")
 */
class PoolController extends BaseController
{

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="admin_pool_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Pool(), $request, true, true);
    }

    /**
     * @param Request $request
     * @param Pool $pool
     * @return Response
     * @Route("/{id}/edit", name="admin_pool_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pool $pool): Response
    {
        return $this->updateAction($pool, $request, false, true);
    }

    /**
     * @Route("/index",methods={"GET"}, name="pool_admin_index")
     */
    public function index(PoolRepository $poolRepository): Response
    {
        return $this->render(
            'admin/pool_index.html.twig',
            [
                'drivers_by_pool' => $this->getDriversByPool(),
                'pools' => $poolRepository->findBy([], ['priority' => 'asc']),
                'max_drivers' => (int)$this->getDoctrine()->getRepository(PoolConfiguration::class)->findOneByName('max_drivers')->getValue()
            ]
        );
    }

    /**
     * @return array
     */
    protected function getDriversByPool():array
    {
        $output = [];
        foreach ($this->getDoctrine()->getRepository(Driver::class)->findAll() as $driver){
            if($driver->getPool() instanceof Pool){
                $output[$driver->getPool()->getId()]['pool'] = $driver->getPool();
                $output[$driver->getPool()->getId()]['drivers'][] = $driver;
            } else {
                $output[0]['drivers'][] = $driver;
            }
        }
        foreach ($this->getDoctrine()->getRepository(Pool::class)->findAll() as $pool){
            if(!isset($output[$pool->getId()])){
                $output[$pool->getId()] = ['pool'=>$pool,'drivers'=>[]];
            }
        }
        if(!isset($output[0])){
            $output[0]['drivers'] = [];
        }
        uasort($output, static function ($a,$b){
            if(!isset($a['pool'])){
                return true;
            }
            if(!isset($b['pool'])){
                return false;
            }
            return $a['pool']->getPriority() > $b['pool']->getPriority();
        });
        return $output;
    }
}