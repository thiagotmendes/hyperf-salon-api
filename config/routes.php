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
use Hyperf\HttpServer\Router\Router;
use \App\Controller\SalonController;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/salons', function () {
    Router::get('/', [SalonController::class, 'index']);
    Router::post('/', [SalonController::class, 'store']);
    Router::put('/{id}', [SalonController::class, 'update']);
    Router::patch('/{id}', [SalonController::class, 'update']);
    Router::delete('/{id}', [SalonController::class, 'destroy']);
});