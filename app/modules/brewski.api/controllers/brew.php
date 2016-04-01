<?php
class BrewController {
    private $_api;
    private $_brewService;
    
    public function __construct($ApiService, $BrewService) {
        $this->_api = $ApiService;
        $this->_brewService = $BrewService;   
    }   
    
    public function find($req, $res) {
        $this->_api->sendSuccess($res, $this->_brewService->find($req->params['id']));
    }
    
    public function test($res, $res) {
        $this->_brewService->create(1256);   
    }
}