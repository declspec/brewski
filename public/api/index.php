<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../app/application.php');

$env = isset($_ENV["PHP_ENV"]) ? $_ENV["PHP_ENV"] : "development";

$app = Application::bootstrap("brewski.api", $env, function($app, $controller) {
    $errorController = $controller->create("ErrorController");
    $recipeController = $controller->create('RecipeController');
    
    $app->post('/recipe', array($recipeController, 'save'));
    $app->post('/recipe/:id(\d+)', array($recipeController, 'save'));
    $app->get('/recipe/:id(\d+)', array($recipeController, 'find'));

    $app->all('*', array($errorController, "notFound"));
    $app->error(array($errorController, "serverError"));
});

$app->run();