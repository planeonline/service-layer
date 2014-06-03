<?php

namespace RESTFulPhalcon;

use RESTFulPhalcon\RestResponse\RestResponseResult;

class RestResponse {

    protected $_metadata = array(
        "url" => null,
        "endpoint" => null,
        "method" => null,
        "results" => 0,
        "success" => 0,
        "failed" => 0
    );
    protected $_results = array();

    public function __construct($url = null, $endpoint = null, $method = null) {
        if (!is_null($url)) {
            $this->setUrl($url);
        }
        if (!is_null($endpoint)) {
            $this->setEndPoint($endpoint);
        }
        if (!is_null($method)) {
            $this->setMethod($method);
        }
    }        

    public function setUrl($url) {
        $this->_metadata['url'] = $url;
    }

    public function getUrl() {
        return $this->_metadata['url'];
    }

    public function setEndpoint($endpoint) {
        $endpoint = explode('?', $endpoint)[0];
        $this->_metadata['endpoint'] = $endpoint;
    }

    public function getEndpoint() {        
        return $this->_metadata['endpoint'];
    }

    public function addResult(RestResponseResult $result) {
        $this->_results[] = $result;
        $this->_calculateCounts($result);       
        
        if(is_null($this->getMethod())){
            $this->setMethod($result->getMethod());
        }
    }
    
    /**
     * To return all results converted to array
     * @return array
     */
    public function getResults() {
        $results = array();
        foreach ($this->_results as $result) {
            $results[] = $result->toArray();
        }
        return $results;
    }
    
    public function setMethod($method){
        $this->_metadata['method'] = $method;
        
        if($method == 'GET'){
            unset($this->_metadata['results']);
            unset($this->_metadata['success']);
            unset($this->_metadata['failed']);
        }else{
            $this->_metadata['results'] = 0;
            $this->_metadata['success'] = 0;
            $this->_metadata['failed'] = 0;
        }
    }
    
    public function getMethod(){
        return $this->_metadata['method'];
    }

    protected function _calculateCounts(RestResponseResult $result) {

        if(!isset($this->_metadata['success']) || !isset($this->_metadata['failed']) || !isset($this->_metadata['results'])){
            return;
        }
        
        if ($result->isSuccess()) {
            $this->_metadata['success'] = $this->_metadata['success'] + 1;
        } else {
            $this->_metadata['failed'] = $this->_metadata['failed'] + 1;
        }

        $this->_metadata['results'] = $this->_metadata['failed'] + $this->_metadata['success'];
    }

    public function __toString() {

        $resultSet = array(
            'metadata' => $this->_metadata,
            'results' => $this->getResults(true)
        );

        return json_encode($resultSet);
    }

}
