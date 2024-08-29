<?php
use App\Controller\PageController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('index', '/')
        ->controller([PageController::class, 'index'])
    ;

    $routes->add('portfolio', '/portfolio')
        ->controller([PageController::class, 'portfolio'])
    ;
};
