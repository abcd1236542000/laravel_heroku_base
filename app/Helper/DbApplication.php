<?php

namespace App\Helper;

class DbApplication
{
    /*
     *  \App\Helper\DbApplication::dumpQuery();
     *    
     */
    static protected $dump_query_status = false;

    public static function getStatus()
    {
        return self::$dump_query_status;
    }
    public static function dumpQuery()
    {
        self::$dump_query_status = true;
    }
}
