<?php
require(__DIR__ . '/../models/recipe.php');

class RecipeController {
    private $_api;
    private $_brewService;
    private $_errorHandler;
    
    public function __construct($ApiService, $BrewService, $ErrorHandlerService) {
        $this->_api = $ApiService;
        $this->_recipeService = $RecipeService;   
        $this->_errorHandler = $ErrorHandlerService;
    }   
    
    public function find($req, $res) {
        try {
            $this->_api->sendSuccess($res, $this->_brewService->find($req->params['id']));
        }
        catch(Exception $ex) {
            $this->_errorHandler->handleUnchecked($ex);
            $this->_api->sendFailure($res, $this->_errorHandler->getLastError()); 
        }
    }
}