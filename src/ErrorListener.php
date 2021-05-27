<?php


namespace App;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * ErrorListener
 * ErrorListener.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 14/05/2021
 */
class ErrorListener
{
    public function onKernelException(ExceptionEvent $event):void
    {
        //$event->setResponse(new Response($event->getThrowable()->getMessage()));
    }
}