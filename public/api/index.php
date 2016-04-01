<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../app/application.php');

$env = isset($_ENV["PHP_ENV"]) ? $_ENV["PHP_ENV"] : "development";

$start = -microtime(true);

$app = Application::bootstrap("brewski.api", $env, function($app, $controller) {
    $errorController = $controller->create("ErrorController");
    $recipeController = $controller->create('RecipeController');
    $brewController = $controller->create('BrewController');
    
    $app->post('/recipe', array($recipeController, 'save'));
    $app->post('/recipe/:id(\d+)', array($recipeController, 'save'));
    $app->get('/recipe/:id(\d+)', array($recipeController, 'find'));
    
    $app->get('/brew/:id(\d+)', array($brewController, 'find'));
    $app->get('/brew/test', array($brewController, 'test'));

    $app->all('*', array($errorController, "notFound"));
    $app->error(array($errorController, "serverError"));
});

$app->run();

error_log($_SERVER['REQUEST_URI'] . ": " . (($start + microtime(true)) * 1000) . "ms\n", 3, "access.log");