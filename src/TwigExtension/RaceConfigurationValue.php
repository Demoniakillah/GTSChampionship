<?php


namespace App\TwigExtension;

use App\Entity\RaceConfiguration;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * RaceConfigurationValue
 * RaceConfigurationValue.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 12/05/2021
 */
class RaceConfigurationValue extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('configurationValue', [$this,'getValueFromConfiguration'])
        ];
    }


    /**
     * @param RaceConfiguration $configuration
     * @return string
     */
    public function getValueFromConfiguration(RaceConfiguration $configuration):string
    {
        if($configuration->getParameter()->getType() === 'array'){
            return json_decode($configuration->getParameter()->getAvailableValues(), true)[(int)$configuration->getValue()];
        }
        return $configuration->getValue();
    }
}