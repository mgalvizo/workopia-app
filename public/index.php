<!-- ENTRY POINT FILE FOR THE APP -->
<!-- Make sure the configuration of your auto.workopia-app.test.conf includes the public folder as Document Root -->
<?php
// Helpers is available for the whole app
require '../helpers.php';
require basePath('Database.php');
require basePath('Router.php');

// Instantiate router
$router = new Router();

// Require routes file
$routes = require basePath('routes.php');

// Get current uri
$uri = $_SERVER['REQUEST_URI'];
// inspectAndDie(($uri));

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];
// inspectAndDie(($method));

// Route the request
$router->route($uri, $method);
?>