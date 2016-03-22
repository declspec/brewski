<?php
require(__DIR__ . '/services/recipe.php');
require(__DIR__ . '/controllers/recipe.php');
require(__DIR__ . '/services/brew.php');
require(__DIR__ . '/controllers/brew.php');

return function($dm) {
    $module = $dm->module('brewski.api', array('core.api'));   
    
    $module->service('RecipeService');
    $module->controller('RecipeController');
    
    $module->service('BrewService');
    $module->controller('BrewController');
};