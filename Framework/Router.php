<?php
// Add the namespace for this class specified in out list of namespaces in composer.json
// Namespaces avoid conflicts if there is another class with the same name in a different directory
namespace Framework;

// Using the error controller
use App\Controllers\ErrorController;

class Router {
  protected $routes = [];

  /**
   * Add a new route
   *
   * @param string $method
   * @param string $uri
   * @param string $action
   * @return void
   */
  public function registerRoute($method, $uri, $action) {
    // Creating an array of the controller and the method e.g. ['HomeController', 'index']
    // list assigns variables as if they were an array (like JavaScript array destructuring)
    list($controller, $controllerMethod) = explode('@', $action);
    // inspectAndDie($controller); 


    // Add entries to the array
    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller,
      'controllerMethod' => $controllerMethod,
    ];
  }

  /**
   * Add a GET route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function get($uri, $controller) {
    $this->registerRoute('GET', $uri, $controller);
  }

  /**
   * Add a POST route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function post($uri, $controller) {
    $this->registerRoute('POST', $uri, $controller);
  }

  /**
   * Add a PUT route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function put($uri, $controller) {
    $this->registerRoute('PUT', $uri, $controller);
  }

  /**
   * Add a DELETE route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function delete($uri, $controller) {
    $this->registerRoute('DELETE', $uri, $controller);
  }

  // Not needed since we are using the Errorcontroller now
  // /**
  //  * Load error page
  //  * 
  //  * @param int $httpCode
  //  * @return void
  //  * 
  //  */
  // public function error($httpCode = 404) {
  //   http_response_code($httpCode);
  //   loadView("error/{$httpCode}");
  //   exit;
  // }

  /**
   * Route the request
   * 
   * @param string $uri
   * @return void
   */
  public function route($uri) {
    // Get HTTP method
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    // inspectAndDie(($method));

    // Check for _method hidden input
    if ($requestMethod === 'POST' && isset($_POST['_method'])) {
      // Override the request method with the value of _method
      $requestMethod = strtoupper($_POST['_method']);
    }

    foreach($this->routes as $route) {
      // Split the current uri into segments
      // trim strips whitespace (or other characters) from the beginning and end of a string
      $uriSegments = explode('/', trim($uri, '/'));
      // inspectAndDie($uriSegments);

      // Split the current iterated route URI into segments
      $routeSegments = explode('/', trim($route['uri'], '/'));
      // inspect($routeSegments); // Must use inspect because is an array of data

      $match = true;

      // Check if number of segments matches and the current iterated route method matches the request method
      if (count($uriSegments) === count($routeSegments) && strtoupper($route['method']) === $requestMethod) {
        $params = [];

        $match = true;

        for ($i = 0; $i < count($uriSegments); $i++) {
          // If the uris do NOT match and there is no params
          // preg_match performs a regular expression match
          if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
            $match = false;
            break;
          }

          // Check for the param and add it to the params array, will map whatever we add as a placeholder in the routes
          if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
            // inspectAndDie($matches);
            // inspectAndDie($uriSegments[$i]);
            $params[$matches[1]] = $uriSegments[$i];
            // inspectAndDie($params);
          }
        }

        if ($match) {
        // Extract controller and controller method from the array of routes
        $controller = 'App\\Controllers\\' . $route['controller'];
        $controllerMethod = $route['controllerMethod'];

        // Instantiate controller and call the method
        $controller = new $controller();
        $controller->$controllerMethod($params);
        return;
        }
      }
    }

    // Call static methods from the class with scope resolution operator
    ErrorController::notFound();

    // If no routes are matched process error page, 404 is default
    // $this->error();
  }
}
?>