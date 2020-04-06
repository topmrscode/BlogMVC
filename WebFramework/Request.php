<?php

namespace WebFramework;

class Request {

  public $base;
  public $route;
  public $method;
  public $params;

  public function __construct() {
    $this->retrieveRoute();
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->params = $_REQUEST;
  }

  /**
    * Retrieve requested route and remove query params from it in order
    * to redirect to the correct route.
   */
  public function retrieveRoute() {
    $url = trim(
        parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
    );
    $pos = strpos($url, '/');
    $this->base = substr($url, 0, ($pos) !== false ? $pos + 1 : 0);
    // $this->route = $url;
    $this->route = substr($url, ($pos) !== false ? $pos : 0);
  }
}
