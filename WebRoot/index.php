<?php

error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../WebFramework/AutoLoader.php';

use WebFramework\Router;
use WebFramework\Request;

Router::load('../config/routes.php')
  ->dispatch(new Request());

  