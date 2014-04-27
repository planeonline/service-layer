<?php

namespace RESTFulPhalcon;

use Phalcon\Mvc\Controller,
    RESTFulPhalcon\RestRequest as RestRequest;

/**
 * A RESTFul implementation of 
 *
 * @author Ali Bahman abn@webit4.me
 */
class RestController extends Controller {

    protected $_restRequest;

    public function onConstruct(){
//        $this->_initHeader();
    }
            
    public function indexAction() {

        
//        var_dump($params, __FILE__ . ' : ' . __LINE__);
        
//        var_dump($this->getRestRequest(), __FILE__ . ' : ' . __LINE__);
//        var_dump($this->getRestRequest()->getParams(), __FILE__ . ' : ' . __LINE__);
//        die();
    }

    protected function _initHeader() {
        header('Content-Type: application/json');
    }

    protected function _initRestRequest() {
        $this->setRestRequest(new RestRequest());
    }

    public function setRestRequest(RestRequest $request) {
        $this->_restRequest = $request;        
    }

    public function getRestRequest() {

        if (is_null($this->_restRequest)) {
            $this->_initRestRequest();
        }
        return $this->_restRequest;
    }

}
