<?php 
require __DIR__ . "/../includes/app.php";
use MVC\Router;
$router = new Router();
/** APLICATION ROUTING */
// Yours urls here !

$router->checkRoutes();
?>