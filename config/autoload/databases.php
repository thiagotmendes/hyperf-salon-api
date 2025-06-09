<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use function Hyperf\Support\env;

return [
    'default' => [
        'driver' => env('DB_DRIVER', 'mysql'),
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', 3306),
        'database' => env('DB_DATABASE', 'hyperf'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => env('DB_PREFIX', ''),
        'pool' => [
            'min_connections' => 10,
            'max_connections' => 200,
            'connect_timeout' => 10.0,
            'wait_timeout' => 5.0,
            'heartbeat' => -1,
            'max_idle_time' => (float) env('DB_MAX_IDLE_TIME', 60),
        ],
        'cache' => [
            'handler' => Hyperf\ModelCache\Handler\RedisHandler::class,
            'cache_key' => '{mc:%s:m:%s}:%s:%s',
            'prefix' => 'default',
            'ttl' => 3600 * 24, // 24 horas
            'empty_model_ttl' => 600, // evita cache penetration (dados nulos)
            'load_script' => true, // usar Lua script para performance
            'use_default_driver' => true, // forçar o Redis Driver default do app
            'max_idle_time' => 3600, // Quanto tempo pode ficar parado antes de limpar
            'lock' => [
                'enabled' => true, // Ativa Lock
                'ttl' => 3, // Lock dura 3 segundos
                'wait_timeout' => 5, // Espera no máximo 5 segundos por uma lock
                'sleep_us' => 500, // Dorme 500 microsegundos entre tentativas
            ],
        ],
        'commands' => [
            'gen:model' => [
                'path' => 'app/Model',
                'force_casts' => true,
                'inheritance' => 'Model',
                'uses' => '',
                'table_mapping' => [],
            ],
        ],
    ],
];
