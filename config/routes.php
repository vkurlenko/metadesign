<?php

use App\Controller\Api\CalculateController;
use App\Controller\PageController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    // Web
    $routes->add('index', '/')
        ->controller([PageController::class, 'index'])
    ;

    $routes->add('portfolio', '/portfolio')
        ->controller([PageController::class, 'portfolio'])
    ;

    // Api
    $routes->add('calculate', '/api/calculate')
        ->controller([CalculateController::class, 'index'])
    ;
};
