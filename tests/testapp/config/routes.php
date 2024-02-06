<?php
declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routeBuilder): void {
    $routeBuilder->setRouteClass(DashedRoute::class);
};
