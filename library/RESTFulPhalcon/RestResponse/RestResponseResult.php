<?php

namespace RESTFulPhalcon\Response;
use RESTFulPhalcon\Request\RestCriteria;

class RestResponseResult {

    protected $_metadata = array(
        'status' => null,
        'code' => null,
        'model' => null,
        'criteria' => null,
        'start' => null,
        'size' => null,
    );
    protected $_result = array();

    public function __construct($result = null, $metadata = null) {

        if (!is_null($result)) {
            $this->_result = $result;
        }

        if (!is_null($metadata)) {
            $this->_metadata = $metadata;
        }
    }

    public function setStatus($status) {
        $this->_metadata['status'] = $status;
    }

    public function getStatus() {
        return $this->_metadata['status'];
    }

    public function setCode($code) {
        $this->_metadata['code'] = $code;
    }
    
    public function getCode() {
        return $this->_metadata['code'];
    }

    public function setModel($modelName) {
        $this->_metadata['model'] = $modelName;
    }

    public function getMetadata() {
        return $this->_metadata;
    }

    public function isSuccess() {

        $success = false;

        if (substr($this->getCode(), 0, 1) == 2) {
            $success = true;
        }

        return $success;
    }

    public function setResult(array $data) {
        $this->_result = $data;
    }

    public function getResult($jsonFormat = false) {
        return $jsonFormat ? json_encode($this->_result) : $jsonFormat;
    }

    public function toArray($sortMetadataBykey = true) {
        
        if($sortMetadataBykey)
            ksort($this->_metadata);
        
        return array(
            'metadata' => $this->_metadata,
            'result' => $this->_result
        );
    }
    
    public function setCriteria(RestCriteria $criteria){        
        $this->_metadata['criteria'] = $criteria->getParams();
        $this->_metadata['start'] = (int)$criteria->getLimit()['offset'];
        $this->_metadata['size'] = (int)$criteria->getLimit()['number'];        
    }
    
    public function setTotal($total){
        $this->_metadata['total'] = (int) $total;
    }
    
    public function setCount($count){
        $this->_metadata['count'] = (int) $count;
    }
    
    

}
