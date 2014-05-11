<?php

namespace RESTFulPhalcon;

use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Criteria;
use RESTFulPhalcon\Request\RestCriteria;

/**
 * Description of RestRequest
 *
 * @author Ali Bahman <abn@webit4.me>
 */
class RestRequest extends Request{

    protected $_criteria;
    
    protected $_expands;
    
    protected $_params;
    
    public function __construct($params = null) {
        
        if(is_null($params)){
            $this->_initParams();
        }else{
            $this->setParams($params);
        }
    }
    
    protected function _initCriteria(){
        $this->_criteria = new RestCriteria($this);          
        
//        
//        $this->_criteria->where("id = :id:");
//        $this->_criteria->inWhere('id', [1, 2, 3]);
//        $this->_initCriteriaOrders();
    }
    
    protected function _initCriteriaOrders(){
        
        $order = $this->_params['order'];
        var_dump($order, __FILE__ . ' : ' . __LINE__);
        die();
        $this->_criteria->orderBy('id');
    }
    
    public function setCriteria($criteria = null){
        
        if(is_null($criteria)){
            $this->_initCriteria();
        }else{
            $this->_criteria = $criteria;
        }
        
    }
    
    public function getCriteria(){
        
        if(is_null($this->_criteria)){
            $this->setCriteria();
        }
        
        return $this->_criteria;
    }
    
    protected function _initExpands(){
        $this->_expands = array();
    }
    public function setExpands($expands,$heirarchical = true){
        
        $paths = explode(',', $expands);
        if (!$heirarchical) {
            return $paths;
        }
        $expand = array();
        foreach ($paths as $path) {
            $parts = explode('/', $path);
            $e = & $expand;
            while (count($parts) > 0) {
                $part = array_shift($parts);
                $part = str_replace(';', ',', $part);
                if (!isset($e[$part])) {
                    $e[$part] = array();
                }


                $e = & $e[$part];
            }
        }

        $this->_expands = $expand;
        
    }
    
    public function getExpands(){
        if(is_null($this->_expands)){
            $this->_initExpands();
        }
        return $this->_expands;
    }
    
    protected function _initParams(){
        
        switch ($this->getMethod()) {
                case 'GET':
                    $params = $this->getQuery();
                    break;
                case 'POST':
//                    $params = $this->get();
                    $params = (array) $this->getJsonRawBody();
                    break;
                default:
                    $params = array();
            } 
            
        $this->setParams($params);
    }    
    
    public function setParams($params){
        $this->_params = $params;
    }
    
    public function getParams($publicOnly = false){
        if(is_null($this->_params)){
            $this->_initParams();
        }
        
        $params = $this->_params;
        
        if($publicOnly){                 
            array_walk($params, function(&$value,$key) use(&$params){                
                if(substr($key,0,1) == '_'){                    
                    unset($params[$key]);
                }
            });
        }
        
        return $params;
        
    }
        
}
