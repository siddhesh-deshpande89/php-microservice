<?php

// Router Instance
$router = new \Bramus\Router\Router();

// Define routes
$router->setNamespace('\App\Controllers');
$router->post('/transactions', 'ProductController@insert');

$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    // ... do something special here
    echo '404 Page not found';
});

// Run it!
$router->run();