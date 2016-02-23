<?php
require(__DIR__ . '/controllers/page.php');

return function($dm) {
    $module = $dm->module('brewski.web', array('core.web'));   
    
    $module->config(function($TemplateServiceProvider, $config) {
        $TemplateServiceProvider->setCompileDirectory(__DIR__ . '/../../.runtime/compiled-views');
        $TemplateServiceProvider->setTemplateDirectory(__DIR__ . '/../../views');
        
        // Setup global variables accessible from any view.
        $TemplateServiceProvider->addGlobals(array(
            'appName' => $config->get('appName'),
            'appVersion' => $config->get('appVersion')
        ));
    });
    
    $module->controller('PageController');    
};