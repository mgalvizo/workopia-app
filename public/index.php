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
// We remove the query string (temporarily) for the listing detail page to work with only listing
// parse_url parses a URL and return its components
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// inspectAndDie(($uri));

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];
// inspectAndDie(($method));

// Route the request
$router->route($uri, $method);
?>