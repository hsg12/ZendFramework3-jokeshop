<?php

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => 'localhost',
                    'user'     => 'jokeshop_user',
                    'password' => 'jokeshop_password',
                    'dbname'   => 'jokeshop',
                ]
            ],
        ],
    ],
];
