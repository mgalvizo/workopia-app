<!-- Make sure the configuration of your auto.workopia-app.test.conf includes the public folder as Document Root -->
<?php
// Helpers is available for the whole app
require '../helpers.php';

// Basic Routing
$routes = [
    '/workopia-app/public/' => 'controllers/home.php',
    '/listings' => 'controllers/listings/index.php',
    '/listings/create' => 'controllers/listings/create.php',
    '404' => 'controllers/error/404.php',
];

$uri = $_SERVER['REQUEST_URI'];
// inspectAndDie(($uri));

// array_key_exists checks if the given key or index exists in the array
if (array_key_exists($uri, $routes)) {
    require(basePath($routes[$uri]));
} else {
    require basePath($routes['404']);
}
?>