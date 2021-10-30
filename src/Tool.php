<?php

namespace App;

/**
 * Class Tool
 * @package App
 */
class Tool
{
    /**
     * @param string $time
     * @return int
     */
    public static function timeToMilli(string $time):int
    {
        $matches = [];
        if(!preg_match('/(\d+):(\d{2}).(\d{3})/', $time, $matches)){
            throw new \RuntimeException("Time $time has not valid format");
        }
        [$full,$minute,$second,$milli] = $matches;
        return $minute*60*1000+$second*1000+$milli;
    }

    /**
     * @param $milli
     * @return string
     */
    public static function milliToTime($milli):string
    {
        $minute = str_pad(floor($milli/(60*1000)),2,"0",STR_PAD_LEFT);
        $second = str_pad(floor(($milli/1000)%60),2,"0",STR_PAD_LEFT);
        $milli = str_pad(substr($milli,-3),3,"0",STR_PAD_LEFT);
        return "$minute:$second.$milli";
    }

    /**
     * @param string $timeA
     * @param string $timeB
     * @return string
     */
    public static function timeAdd(string $timeA, string $timeB):string
    {
        $totalMilli = self::timeToMilli($timeA) + self::timeToMilli($timeB);
        return self::milliToTime($totalMilli);
    }

    /**
     * @param string $timeA
     * @param string $timeB
     * @return string
     */
    public static function timeSub(string $timeA, string $timeB):string
    {
        $totalMilli = self::timeToMilli($timeA) - self::timeToMilli($timeB);
        return self::milliToTime($totalMilli);
    }
}