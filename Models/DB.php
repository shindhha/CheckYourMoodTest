<?php

namespace Models;

use yasmf\DataSource;

class DB
{
    private static $dataSource;

    public static function setPDO($dataSource) {
        static::$dataSource = $dataSource;
    }

    public static function getPDO() {
        if (static::$dataSource == null) {
            static::$dataSource = new DataSource(
                $host = '172.20.0.3',
                $port = '3306', # to change with the port your mySql server listen to
                $db = 'check_your_mood', # to change with your db name
                $user = 'root', # to change with your db user name
                $pass = 'root', # to change with your db password
                $charset = 'utf8mb4'
            );
        }
        static::$dataSource->getPDO();
    }
}