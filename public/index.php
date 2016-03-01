<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../app/application.php');

$env = isset($_ENV["PHP_ENV"]) ? $_ENV["PHP_ENV"] : "development";

$app = Application::bootstrap("brewski.web", $env, function($app, $controller) {
    $errorController = $controller->create("ErrorController");
    $pageController = $controller->create("PageController");
    
    $app->all('/recipe*', array($pageController, 'recipe'));

    $app->all('*', array($errorController, "notFound"));
    $app->error(array($errorController, "serverError"));
});

$app->run();