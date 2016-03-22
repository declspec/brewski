<?php
class BrewController {
    private $_api;
    private $_brewService;
    private $_errorHandler;
    
    public function __construct($ApiService, $BrewService, $ErrorHandler) {
        $this->_api = $ApiService;
        $this->_brewService = $BrewService;   
        $this->_errorHandler = $ErrorHandler;
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