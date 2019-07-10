<?php
namespace App;

class Test
{
    static function hello()
    {
        echo __CLASS__.": load.\n";
    }

    /**
     * for SOA Server
     * @return array
     */
    static function test1()
    {
        return array('file' => __FILE__, 'method' => __METHOD__);
    }
}