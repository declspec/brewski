<?php
require(__DIR__ . '/../models/recipe.php');

class RecipeController {
    private $_api;
    private $_recipeService;
    private $_errorHandler;
    
    public function __construct($ApiService, $RecipeService, $ErrorHandlerService) {
        $this->_api = $ApiService;
        $this->_recipeService = $RecipeService;   
        $this->_errorHandler = $ErrorHandlerService;
    }   
    
    public function save($req, $res) {
        try {
            $model = RecipeModel::bind($req->body);

            if (!$this->validateRecipe($model))
                $this->_api->sendFailedValidation($res, $model->getErrors());
            else {
                $recipe = $this->_recipeService->save($model->unwrap());
                $this->_api->sendSuccess($res, $recipe);
            }
        }
        catch(Exception $ex) {
            $this->_errorHandler->handleUnchecked($ex);
            $this->_api->sendFailure($res, $this->_errorHandler->getLastError());  
        }
    }
    
    public function find($req, $res) {
        try {
            $this->_api->sendSuccess($res, $this->_recipeService->find($req->params['id']));
        }
        catch(Exception $ex) {
            $this->_errorHandler->handleUnchecked($ex);
            $this->_api->sendFailure($res, $this->_errorHandler->getLastError()); 
        }
    }
    
    public function validateRecipe($model) {
        if (!$model->validate())   
            return false;
        
        $steps = $model->get('steps');
        $ingredients = $model->get('ingredients');
        
        if (is_array($steps))
            $steps = array_filter($steps, function($s) { return !empty($s['content']); });
        
        if (is_array($ingredients))
            $ingredients = array_filter($ingredients, function($i) { return !empty($i['description']); });
        
        if (!is_array($steps) || count($steps) < 1) {
            $model->addError('steps', 'Please specify at least one step on how to create the recipe.');
            return false;  
        }
        
        if (!is_array($ingredients) || count($ingredients) < 1) {
            $model->addError('ingredients', 'Please specify at least one ingredient for the recipe.');
            return false;   
        }
        
        $model->set('steps', $steps);
        $model->set('ingredients', $ingredients);
        
        return true;
    }
}