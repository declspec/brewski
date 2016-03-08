<?php
class RecipeController {
    private $_api;
    private $_recipeService;
    
    public function __construct($ApiService, $RecipeService) {
        $this->_api = $ApiService;
        $this->_recipeService = $RecipeService;   
    }   
    
    public function save($req, $res) {
        try {
            $recipe = isset($req->query->id) && $res->params->id
                ? $this->_recipeService->update($req->body)
                : $this->_recipeService->create($req->body);
                
            $this->_api->sendSuccess($res, $recipe);
        }
        catch(Exception $ex) {
            $this->_api->sendFailure($res, $ex);   
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