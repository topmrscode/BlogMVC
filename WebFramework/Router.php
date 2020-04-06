<?php

namespace WebFramework;

use WebFramework\AppController;

class Router {

  private $routes = [
    'GET' => [],
    'POST' => [],
  ];

  /**
   * Use a routing file to create new routes and link theses to a controller
   * and an handler.
   *
   * @param string $file - Path of the file describing the routes.
   * @return Router - Static instance of the Router class.
   */
  public static function load(string $file) {
    $router = new static();
    require $file;

    return $router;
  }

  /**
   * Link a new route to a controller and a handler.
   *
   * @param string $requestType - Type of request: GET or POST.
   * @param string $route - Route to bind.
   * @param Controller $controller - Class containing the handler method.
   * @param function $handler - Function used to handle incoming requests on the route.
   */
  public function use(string $requestType, string $route, AppController $controller, string $handler) {
    $this->routes[$requestType][$route] = [
      'controller' => $controller,
      'handler' => $handler
    ];
  }

  /**
   * Call the handler of a route.
   *
   * @param Request $request - A simplified and object-style abstraction
   *   of the incomings HTTP requests.
   * @param Controller $controller - The controller class containing the route handler.
   * @param string $handler - Name of the route handler which is orcherstrating
   *   every interations wih the views and the database.
   */
  public function handle(Request $request, AppController $controller, string $handler) {
    if (!method_exists($controller, $handler)) {
      $controller_name = get_class($controller);
      throw new \Exception("{$controller_name} does not have {$handler}");
    }

    $controller->$handler($request);
  }

  /**
   * Manage incoming requests and forward theses to the correct handlers.
   *
   * @param Request $request - A simplified and object-style abstraction
   *   of the incomings HTTP requests.
   */
  public function dispatch(Request $request) { // ca va prendre la requete de l utilisateur dans l url et appeler le handler correspondant sinon affiche page d erreur
    if (array_key_exists($request->method, $this->routes)
        && array_key_exists($request->route, $this->routes[$request->method])) {
      $route_handler = $this->routes[$request->method][$request->route];
      $this->handle($request, $route_handler['controller'], $route_handler['handler']);
    } else {
      // TODO: Implement a 404 view and render it with TWIG when the
      // requested route is invalid
      echo '<h1>Page not found</h1>';
      // var_dump($this->routes);
      // var_dump($requestroute);
    }
  }

}
