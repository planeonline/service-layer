<?php

namespace RESTFulPhalcon\Request;

use Phalcon\Mvc\Model\Criteria;

class RestCriteria extends Criteria {

    protected $_restRequest;
    protected $_originalParameters;
    protected $_whereParams;

    /**
     * 
     * @param RESTFulPhalcon\RestRequest $restRequest
     */
    public function __construct($restRequest) {

        $this->_restRequest = $restRequest;
        $this->_originalParameters = $restRequest->getParams(true);

        $this->_processOrders();
        $this->_processWheres();

    }

    protected function _processWheres() {               
        $this->_processSingularWheres();
        $this->_processInWheres();
        $this->_processBetweenWheres();
        
    }

    protected function _processSingularWheres() {

        $singularConditions = array();
        foreach ($this->_getWhereParamsOnly() as $key => $value) {
            if (strpos($value, '[')===false) {
                $singularConditions[$key] = $value;
            }
        }

        foreach ($singularConditions as $key => $value) {

            if(substr($value, 0, 1) == '|'){
                $this->_processOrWhere($key, substr($value, 1));
            }else{
                $this->_processAndWhere($key, $value);
            }
 
        }

    }


    /**
     * Or normal key,value paires in the string query
     * e.g. 
     * user=25  AND make = 34
     * user=!25 AND make = 34
     */
    protected function _processAndWhere($key, $value) {        
        $condition = $this->_buildCondition($key, $value);        
        $this->andWhere($condition);
    }

    /**
     * Or normal key,value paires in the string query
     * e.g. 
     * user=25  OR make = 34
     * user=!25 OR make = 34
     */
    protected function _processOrWhere($key, $value) {        
        $condition = $this->_buildCondition($key, $value);        
        $this->orWhere($condition);        
    }

    /**
     * Or normal key,value paires in the string query
     * e.g. 
     * user=25  OR make = 34
     * user=!25 OR make = 34
     */
    protected function _buildCondition($key, $value) {
        
        $condition = $key;
        switch (substr($value, 0, 1)){
            case '!':
                $condition .= ' <> "' . substr($value, 1) . '"';
                break;
            case '<':
                $condition .= ' < "' . substr($value, 1) . '"';
                break;
            case '>':
                $condition .= ' > "' . substr($value, 1) . '"';
                break;
            default:                
                $condition .= ' = "' . $value . '"';
        }
        
       return $condition;
        
    }

    /**
     * Or normal key,value paires in the string query
     * e.g. 
     * user IN (23, 2, 10)
     */
    protected function _processInWheres() {
        
        $multipleConditions = array();
        foreach ($this->_getWhereParamsOnly() as $key => $value) {            
            if (strpos($value, '[') !== false &&
                    !preg_match('/\-/', $value)) {
                $multipleConditions[$key] = $value;
            }
        }
        
        foreach ($multipleConditions as $key => $value) {
            preg_match('/\[(.*)\]/', $value,$trimedValue);
            $trimedValue = $trimedValue[1];
            if(substr($value, 0, 1) == '!'){
                $items = explode(',', $trimedValue);                
                $this->notInWhere($key, $items);
            }else{
                $items = explode(',', $trimedValue);                                
                $this->inWhere($key, $items);                
            }
 
        }
                   
    }

    /**
     * Or normal key,value paires in the string query
     * e.g. 
     * user BETWEEN 10 AND 15
     */
    protected function _processBetweenWheres() {
            
        $multipleConditions = array();
        foreach ($this->_getWhereParamsOnly() as $key => $value) {            
            if (strpos($value, '[') !== false && 
                    preg_match('/\-/', $value)) {
                $multipleConditions[$key] = $value;
            }
        }
        
        foreach ($multipleConditions as $key => $value) {
            preg_match('/\[(.*)\]/', $value,$trimedValue);
            if(substr($value, 0, 1) == '!'){
                $items = explode('-', $trimedValue);                
                $this->notBetweenWhere($key, $items[0],$items[1]);
            }else{                
                $items = explode('-', $trimedValue[1]);                                
                $this->betweenWhere($key, $items[0],$items[1]);                
            }
 
        }
    }

    protected function _getWhereParamsOnly() {

        if (is_null($this->_whereParams)) {
            $this->_whereParams = array();
            $ignoreKeys = array('order', 'expand', 'limit');

            foreach ($this->_originalParameters as $key => $value) {
                if (!in_array($key, $ignoreKeys)) {
                    $this->_whereParams[$key] = $value;
                }
            }
        }

        return $this->_whereParams;
    }

    protected function _processOrders() {

        $order = isset($this->_originalParameters['order']) ? $this->_originalParameters['order'] : false;

        $pattern = "/\[(.*?)\]/";

        if (preg_match($pattern, $order, $matches)) {
            $order = $matches[1];
        }

        if($order !== FALSE){
            $this->orderBy($order);
        }
    }

}
