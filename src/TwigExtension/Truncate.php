<?php


namespace App\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Truncate
 * Truncate.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 04/06/2021
 */
class Truncate extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('truncate', [$this, 'truncate']),
        ];
    }


    public function truncate($text, $maxWords = 200): string
    {
        $textTooKeep = [];
        $textExploded = explode(" ", $text);
        if(count($textExploded) > $maxWords) {
            for ($i = 0; $i < $maxWords; $i++) {
                $textTooKeep[] = $textExploded[$i];
            }
            if (count($textTooKeep) < count($textExploded)) {
                $textTooKeep[] = '<br/><span class="btn btn-link see_more"> see more...</span>';
            }
        }
        return implode(' ', $textTooKeep);
    }
}