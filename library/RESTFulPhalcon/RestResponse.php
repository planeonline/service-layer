<?php

namespace RESTFulPhalcon;

class RestResponse {

    protected $_metadata = array(
        "url" => null,
        "endpoint" => null,
        "method" => null,
        "results" => 0,
        "success" => 0,
        "fialed" => 0
    );
    protected $_results = array();

    public function __construct($url = null, $endpoint = null, $method = null) {
        if (!is_null($url)) {
            $this->_metadata['url'] = $url;
        }
        if (!is_null($endpoint)) {
            $this->_metadata['endpoint'] = $endpoint;
        }
        if (!is_null($method)) {
            $this->_metadata['method'] = $method;
        }
    }

    public function setUrl($url) {
        $this->_metadata['url'] = $url;
    }

    public function setEndpoint($endpoint) {
        $this->_metadata['endpoint'] = $endpoint;
    }

    public function addResult(Response\RestResponseResult $result) {
        $this->_results[] = $result;
        $this->_calculateCounts($result);
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

        if ($result->isSuccess()) {
            $this->_metadata['success'] = $this->_metadata['success'] + 1;
        } else {
            $this->_metadata['fialed'] = $this->_metadata['fialed'] + 1;
        }

        $this->_metadata['results'] = $this->_metadata['fialed'] + $this->_metadata['success'];
    }

    public function __toString() {

        $resultSet = array(
            'metadata' => $this->_metadata,
            'results' => $this->getResults(true)
        );

        return json_encode($resultSet);
    }

}
