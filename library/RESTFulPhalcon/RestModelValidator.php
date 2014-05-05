<?php

namespace RESTFulPhalcon;

abstract class RestModelValidator implements \Iterator {

    protected $_modelName;
    protected $_validators;
    protected $_index = 0;
    
    public function __construct() {
        $this->addValidators();
    }


    abstract public function addValidators();    
    
    protected function getModelName(){
        if(is_null($this->_modelName)){
            $this->_modelName = str_replace('Validator', '', get_class($this));
        }
        return $this->_modelName;
    }
    
    Public function add($validator) {
        $this->_validators[] = $validator;
    }
    
    
    public function getMessages(){
        $messages = array();
        foreach($this->_validators as $validator){
            
            $validatorMessages = array();
            if(!is_null($validator->getMessages())){
                foreach($validator->getMessages() as $message){
                    $validatorMessages[][$message->getField()] = $message->getMessage();
                }
            }
            $messages['Validation messages'] = $validatorMessages;
        }
        
        return $messages;
    }

    public function current() {
        return $this->_validators[$this->_index];
    }

    public function key() {
        return $this->_index;
    }

    public function next() {
        $this->_index++;
    }

    public function rewind() {
        $this->_index = 0;
    }

    public function valid() {
        return (isset($this->_validators[$this->_index]));
    }
    

}
