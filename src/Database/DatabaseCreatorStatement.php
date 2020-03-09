<?php

namespace Miracuthbert\Multitenancy\Database;

class DatabaseCreatorStatement
{
    /**
     * Create statement for "mysql" database.
     *
     * @param $database
     * @param $charset
     * @param $collation
     * @return string
     */
    public static function mysqlCreateDatabase($database, $charset, $collation)
    {
        return sprintf("CREATE DATABASE IF NOT EXISTS `%s`%s%s", $database, $charset, $collation);
    }

    /**
     * Create statement for "pgsql" database.
     *
     * @param $database
     * @param $charset
     * @param $collation
     * @return string
     */
    public static function pgsqlCreateDatabase($database, $charset, $collation)
    {
        return sprintf("CREATE DATABASE %s%s%s", $database, $charset, $collation);
    }
}
