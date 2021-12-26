<?php


namespace App;


class Tools
{
    /**
     * @param string $a
     * @param string $b
     * @return bool
     */
    public static function compareTimesGT(string $a, string $b):bool
    {
        return self::timeToMilli($a) > self::timeToMilli($b);
    }

    /**
     * @param string $time
     * @return int
     */
    public static function timeToMilli(string $time): int
    {
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        $milliseconds = 0;
        $matches = [];
        if (preg_match('/(\d{2}):(\d{2}):(\d{2}).(\d{3})/', $time, $matches)) {
            [$full, $hours, $minutes, $seconds, $milliseconds] = $matches;
        } elseif (preg_match('/(\d{2}):(\d{2}).(\d{3})/', $time, $matches)) {
            [$full, $minutes, $seconds, $milliseconds] = $matches;
        }
        return
            $hours * 60 * 60 * 1000
            +
            $minutes * 60 * 1000
            +
            $seconds * 60
            + $milliseconds;
    }
}