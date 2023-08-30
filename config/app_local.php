<?php

return [

    'debug' => true,


    'Security' => [
        'salt' => env('SECURITY_SALT', 'b20485d7f30ced17fd620146954a482112f2b51724c7e190a93e7481c2190660'),
    ],


    'Datasources' => [
        'default' => [
    
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            'username' => 'root',
            'password' => 'sunit@1234',
            'database' => 'cake_cms',
            'encoding' => 'utf8mb4',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
        ],

        /*
         * The test connection is used during the test suite.
         */

        // 'test' => [
        //     'host' => 'localhost',
        //     //'port' => 'non_standard_port_number',
        //     'username' => 'my_app',
        //     'password' => 'secret',
        //     'database' => 'test_myapp',
        //     //'schema' => 'myapp',
        //     'url' => env('DATABASE_TEST_URL', 'sqlite://127.0.0.1/tests.sqlite'),
        // ],
    ],


    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => null,
            'password' => null,
            'client' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],
];
