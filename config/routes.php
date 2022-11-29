<?php

use App\Controller\ClientController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;


return function (RoutingConfigurator $routes) {
    $routes->add('client_info_php', 'client/info/php/{prenom}')
        ->controller([ClientController::class, 'info'])
        ->requirements(["prenom"=>"^(?!-).+[a-zA-Zéèà][^ .](?<!-)$"])
    ;

};