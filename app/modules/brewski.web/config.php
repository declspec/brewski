<?php
require(__DIR__ . '/controllers/page.php');

return function($dm) {
    $module = $dm->module('brewski.web', array('core.web'));   
    
    $module->config(function($TemplateServiceProvider) {
        $TemplateServiceProvider->setCompileDirectory(__DIR__ . '/../../.runtime/compiled-views');
        $TemplateServiceProvider->setTemplateDirectory(__DIR__ . '/../../views');
    });
    
    $module->controller('PageController');    
};