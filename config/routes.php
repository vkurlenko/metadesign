<?php
use App\Controller\PageController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('index', '/')
        ->controller([PageController::class, 'index'])
    ;

    $routes->add('about', '/about')
        ->controller([PageController::class, 'about'])
    ;

    $routes->add('contact', '/contact')
        ->controller([PageController::class, 'contact'])
    ;

    $routes->add('service', '/service')
        ->controller([PageController::class, 'service'])
    ;

    $routes->add('portfolio', '/portfolio')
        ->controller([PageController::class, 'portfolio'])
    ;
};
