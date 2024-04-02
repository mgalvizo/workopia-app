<?php
// Add the namespace for this class specified in out list of namespaces in composer.json
// Namespaces avoid conflicts if there is another class with the same name in a different directory
namespace Framework;

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

  /**
   * Load error page
   * 
   * @param int $httpCode
   * @return void
   * 
   */
  public function error($httpCode = 404) {
    http_response_code($httpCode);
    loadView("error/{$httpCode}");
    exit;
  }

  /**
   * Route the request
   * 
   * @param string $uri
   * @param string $method
   * @return void
   */
  public function route($uri, $method) {
    foreach($this->routes as $route) {
      if ($route['uri'] === $uri && $route['method'] === $method) {
        // Extract controller and controller method from the array of routes
        $controller = 'App\\Controllers\\' . $route['controller'];
        $controllerMethod = $route['controllerMethod'];

        // Instantiate controller and call the method
        $controller = new $controller();
        $controller->$controllerMethod();
        return;

        // require basePath('App/' . $route['controller']);
      }
    }

    // If no routes are matched process error page, 404 is default
    $this->error();
  }
}
?>