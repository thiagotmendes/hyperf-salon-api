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
use App\Controller\SalonController;
use App\Controller\CollaboratorController;
use App\Controller\SlotController;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/salons', function () {
    Router::get('/', [SalonController::class, 'index']);
    Router::post('/', [SalonController::class, 'store']);
    Router::get('/{id}', [SalonController::class, 'show']);
    Router::put('/{id}', [SalonController::class, 'update']);
    Router::patch('/{id}', [SalonController::class, 'update']);
    Router::delete('/{id}', [SalonController::class, 'destroy']);
});

Router::addGroup('/collaborators', function () {
    Router::get('/',[CollaboratorController::class, 'index'] );
    Router::post('/',[CollaboratorController::class, 'store']);
    Router::get('/{id}', [CollaboratorController::class, 'show']);
    Router::put('/{id}',[CollaboratorController::class, 'update']);
    Router::patch('/{id}',[CollaboratorController::class, 'update']);
    Router::delete('/{id}',[CollaboratorController::class, 'destroy']);
});

Router::addGroup('/slots', function () {
    Router::get('/',[SlotController::class, 'index'] );
    Router::post('/',[SlotController::class, 'store']);
    Router::get('/{id}', [SlotController::class, 'show']);
    Router::put('/{id}',[SlotController::class, 'update']);
    Router::patch('/{id}',[SlotController::class, 'update']);
});