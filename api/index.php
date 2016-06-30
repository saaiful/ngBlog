<?php
require_once 'autoload.php';
function dd($data)
{
    echo "<pre>";
    print_r($data);
    echo "<pre>";
    die();
}
$route = new Route();
$route->get("/", "HomeController@index");
$route->get("/display/:string", "HomeController@post");
$route->get("/github/:string", "HomeController@github");
$route->run();
