<?php


namespace App;


use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
        if($_ENV['APP_ENV'] === 'prod') {
            if($event->getThrowable() instanceof UniqueConstraintViolationException){
                $message = 'Duplicate entry';
            } else {
                $message =   $event->getThrowable()->getMessage();
            }
            $event->setResponse(new Response($message));
        }
    }
}