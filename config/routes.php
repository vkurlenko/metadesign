<?php

use App\Controller\Api\CalculateController;
use App\Controller\Api\FeedbackController;
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

    $routes->add('about', '/about')
        ->controller([PageController::class, 'about'])
    ;

    $routes->add('project', '/project/{identifier}')
        ->controller([PageController::class, 'project'])
    ;

    $routes->add('drawing', '/drawing/{identifier}')
        ->controller([PageController::class, 'drawing'])
    ;

    $routes->add('service', '/service/{identifier}')
        ->controller([PageController::class, 'service'])
    ;

    // Api
    $routes->add('calculate', '/api/calculate')
        ->controller([CalculateController::class, 'index'])
    ;

    $routes->add('feedback', '/api/feedback')
        ->controller([FeedbackController::class, 'index'])
    ;
};
