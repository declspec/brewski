<?php
require(__DIR__ . '/services/recipe.php');
require(__DIR__ . '/controllers/recipe.php');

return function($dm) {
    $module = $dm->module('brewski.api', array('core.api'));   
    
    $module->service('RecipeService');
    $module->controller('RecipeController');
};