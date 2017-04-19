<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

// require a db connection
require '../src/config/db.php' ;



$app = new \Slim\App;

// boot the require router file ..

require './bootRouter.php';

$app->run();
