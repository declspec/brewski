<?php
class PageController {
    private $_template;
    
    public function __construct($TemplateService) {
        $this->_template = $TemplateService;   
    }
    
    public function index($req, $res) {
        $res->send($this->_template->render('index.tpl', array('name'=>'Jason')));   
    }   
}