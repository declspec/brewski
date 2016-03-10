<?php
require(__DIR__ . '/../models/recipe.php');

class RecipeController {
    private $_api;
    private $_recipeService;
    
    public function __construct($ApiService, $RecipeService) {
        $this->_api = $ApiService;
        $this->_recipeService = $RecipeService;   
    }   
    
    public function save($req, $res) {
        try {
            $model = RecipeModel::bind($req->body);

            if (!$model->validate())
                $this->_api->sendFailedValidation($res, $model->getErrors());
            else {
                $recipe = $this->_recipeService->save($model->unwrap());
                $this->_api->sendSuccess($res, $recipe);
            }
        }
        catch(Exception $ex) {
            $this->_api->sendFailure($res, $ex);   
            error_log($ex->getTraceAsString());  
        }
    }
    
    public function find($req, $res) {
        try {
            $this->_api->sendSuccess($res, $this->_recipeService->find($req->params['id']));
        }
        catch(Exception $ex) {
            $this->_api->sendFailure($res, $ex); 
        }
    }
}