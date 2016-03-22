<?php
require(__DIR__ . '/services/database.php');
require(__DIR__ . '/services/error-handler.php');

return function($dm) {
    $module = $dm->module("core", array());
    
    $module->config(function($config, $DatabaseProvider, $ErrorHandlerProvider) {
        $dbConfig = $config->get("db");
        
        if ($dbConfig !== null) {
            $DatabaseProvider->setConnectionString($dbConfig["connectionString"]);
            $DatabaseProvider->setCredentials($dbConfig["username"], $dbConfig["password"]);
            
            if (isset($dbConfig["options"]))
                $DatabaseProvider->setOptions($dbConfig["options"]);
        }
        
        $ErrorHandlerProvider->setUncheckedErrorMessage('An unexpected error has occurred while processing your request. Please try again.');
    });
   
    // Database provider
    $module->provider('Database', new DatabaseProvider());
    $module->provider('ErrorHandler', new ErrorHandlerProvider());
};