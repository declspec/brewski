<?php
class UserFriendlyException extends Exception {
    // Rename the regular 'Exception' methods.
    public function getUserFriendlyMessage() {
        return $this->getMessage();    
    }
    
    public function getInnerException() {
        return $this->getPrevious();   
    }
    
    public function __construct($message, Exception $realException = null) {
        parent::__construct($message, 0, $realException);   
    } 
};

class ErrorHandlerProvider {
    private $_defaultMessage;
    
    public function setUncheckedErrorMessage($message) {
        $this->_defaultMessage = $message;   
    }  
    
    public function _get() {
        return new ErrorHandlerService($this->_defaultMessage);   
    }
};

class ErrorHandlerService {
    private $_lastError;
    private $_defaultMessage;
    
    public function __construct($defaultMessage=null) {
        $this->_defaultMessage = !empty($defaultMessage)
            ? $defaultMessage
            : 'An internal server error has occurred.';
    }
    
    public function handleUnchecked(Exception $exception) {
        $this->_lastError = $exception instanceof UserFriendlyException
            ? $exception
            : new UserFriendlyException($this->_defaultMessage, $exception);
        
        // Log unchecked exceptions so they can be followed up.
        error_log($exception);
    }
    
    public function handleChecked($message, Exception $exception = null) {
        $this->_lastError = new UserFriendlyException($message, $exception); 
    }
    
    public function reset() {
        $this->_lastError = null;   
    }
    
    public function getLastError() {
        return $this->_lastError;   
    }
};