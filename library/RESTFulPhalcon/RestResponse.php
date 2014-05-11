<?php

namespace RESTFulPhalcon;

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

    public function setEndpoint($endpoint) {
        $endpoint = explode('?', $endpoint)[0];
        $this->_metadata['endpoint'] = $endpoint;
    }

    public function addResult(Response\RestResponseResult $result) {
        $this->_results[] = $result;
        $this->_calculateCounts($result);
    }
    
    public function setMethod($method){
        $this->_metadata['method'] = $method;
        
        if($method == 'GET'){
            unset($this->_metadata['results']);
            unset($this->_metadata['success']);
            unset($this->_metadata['failed']);
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

    protected function _calculateCounts(Response\RestResponseResult $result) {

        if(!isset($this->_metadata['success']) || !isset($this->_metadata['failed']) || !isset($this->_metadata['results'])){
            return;
        }
        
        if ($result->isSuccess()) {
            $this->_metadata['success'] = $this->_metadata['success'] + 1;
        } else {
            $this->_metadata['fialed'] = $this->_metadata['fialed'] + 1;
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
