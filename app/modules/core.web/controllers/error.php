<?php
class ErrorController {
    // TODO: Replace this for your own custom errors
    private $_template;
    
    public function __construct($TemplateService) {
        $this->_template = $TemplateService;   
    }
    
    public function notFound($req, $res) {
        $res->status(404)->send($this->_template->render("404.html"));
    }  
    
    public function serverError($err, $req, $res) {
        $res->status(500)->send($this->_template->render("500.html"));
    }
};