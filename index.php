<?php

require_once './Autoloader.php';

Autoloader::register();

$request = $_SERVER['REQUEST_METHOD'] . "/" .
    filter_var(trim($_SERVER["REQUEST_URI"], '/'), FILTER_SANITIZE_URL);

    echo $request;

$controller = new Controllers\DatabaseController($request);