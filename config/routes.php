<?php

use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

return static function (RouteBuilder $routes) {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder) {

        $builder->registerMiddleware('csrf', new CsrfProtectionMiddleware(['httpOnly' => true]));
        // $builder->applyMiddleware('csrf');


        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        $builder->connect('/pages/*', ['controller' => 'pages', 'action' => 'display']);
        // $builder->connect('/pages/*', 'Pages::display');

        
        $builder->fallbacks(DashedRoute::class);
    });


    $routes->scope('/articles', function (RouteBuilder $builder){
        $builder->connect('/tagged/*', ['controller' => 'Articles', 'action' => 'tags']);
    });

};
