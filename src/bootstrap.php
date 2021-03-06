<?php
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

$injector = require ('dependencies.php');

$request = $injector->make('Http\HttpRequest');

// Routing
$routeDefinitionCallback = function (RouteCollector $r) {
    $routes = include ('routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        break;
    case Dispatcher::FOUND:

        $className = $routeInfo[1][0];

        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        $class = $injector->make($className);

        $class->$method($vars);
        break;
}