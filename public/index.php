<!-- Make sure the configuration of your auto.workopia-app.test.conf includes the public folder as Document Root -->
<?php
// Helpers is available for the whole app
require '../helpers.php';

require basePath('Router.php');

// Instantiate router
$router = new Router();

// Require routes file
$routes = require basePath('routes.php');

$uri = $_SERVER['REQUEST_URI'];
// inspectAndDie(($uri));

$method = $_SERVER['REQUEST_METHOD'];
// inspectAndDie(($method));

$router->route($uri, $method);
?>