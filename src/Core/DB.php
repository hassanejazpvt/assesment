<?php

declare(strict_types=1);

namespace Hassan\Assesment\Core;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Exception;

class DB
{
    private static $connection;
    private static $queryBuilder;
    private static $queries = [];
    private static $result;

    public static function connect(): void
    {
        if (self::$connection) {
            throw new Error('Database already connected.', 500);
        }

        self::$connection = DriverManager::getConnection([
            'dbname'   => env('DB_NAME'),
            'user'     => env('DB_USER'),
            'password' => env('DB_PASS'),
            'host'     => env('DB_HOST'),
            'port'     => env('DB_PORT'),
            'driver'   => 'pdo_mysql',
        ]);

        try {
            self::$connection->connect();
        } catch (Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }

        self::$queryBuilder = self::$connection->createQueryBuilder();
    }

    public static function query(): QueryBuilder
    {
        return self::$queryBuilder;
    }

    public static function connection(): Connection
    {
        return self::$connection;
    }
}
