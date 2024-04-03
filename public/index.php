<!-- ENTRY POINT FILE FOR THE APP -->
<!-- Make sure the configuration of your auto.workopia-app.test.conf includes the public folder as Document Root -->
<?php
require __DIR__ . '/../vendor/autoload.php';

// Using the namespace
use Framework\Router;
use Framework\Session;

// Needed for flash messages
Session::start();

// Helpers is available for the whole app
require '../helpers.php';

// require basePath('Framework/Database.php');
// require basePath('Framework/Router.php');

// Autoloader allows us to have a bunch of classes in the Framework folder without having to require them individually
// spl_autoload_register register given function as __autoload() implementation
// Not needed if using psr-4 with composer
// spl_autoload_register(function ($class) {
//   $path = basePath('Framework/' . $class . '.php');

//   if (file_exists($path)) {
//     require $path;
//   }
// });

// Instantiate router
$router = new Router();

// Require routes file
$routes = require basePath('routes.php');

// Get current uri
// We remove the query string (temporarily) for the listing detail page to work with only listing
// parse_url parses a URL and return its components
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// inspectAndDie(($uri));

// Route the request
$router->route($uri);
?>