<?php

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => 'mysql.hostinger.in',
                    'user'     => 'u281467562_shop',
                    'password' => 'RCFm1mBNSOaM',
                    'dbname'   => 'u281467562_joke',
                ]
            ],
        ],
    ],
];
