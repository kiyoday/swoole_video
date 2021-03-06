<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time'=>3,
            'buffer_output_size' => 100 * 1024 * 1024,
            'package_max_length' => 100 * 1024 * 1024,
        ],
        'TASK'=>[
            'workerNum'=>4,
            'maxRunningNum'=>128,
            'timeout'=>15
        ]
    ],
    'TEMP_DIR' => '/tmp',
    'LOG_DIR' => '/log',
    'REDIS'=>[
        'host' => 'redis',
        'port' => '6379',
        'time_out' => 3,
    ],
    'STATUS'=>[
        'normal'=>1,
        'auditing'=>0,
        'delete'=>2
    ],
];
