<?php
class PageController {
    private $_template;
    
    public function __construct($TemplateService) {
        $this->_template = $TemplateService;   
    }
    
    public function index($req, $res) {
        $res->send($this->_template->render('index.html', array('name'=>'Jason')));   
    }   
    
    public function recipe($req, $res) {
        $res->send($this->_template->render('recipe/index.html'));   
    }
    
    public function brew($req, $res) {
        $res->send($this->_template->render('brew/index.html'));   
    }
}