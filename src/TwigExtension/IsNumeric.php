<?php


namespace App\TwigExtension;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * IsNumeric
 * IsNumeric.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 07/05/2021
 */
class IsNumeric extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('numeric', [$this, 'isNumeric']),
        ];
    }

    public function isNumeric($value):bool
    {
        if(preg_match('/^\d$/', $value)){
            return true;
        }
        return false;
    }
}