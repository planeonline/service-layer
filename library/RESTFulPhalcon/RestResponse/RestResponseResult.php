<?php
/**
 * Copyright 2014 Takeaway IT Ltd.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP version 5.4
 *
 * @category RESTFul_API
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */

namespace RESTFulPhalcon\RestResponse;

use RESTFulPhalcon\RestRequest\RestCriteria;

/**
 * Class RestResponseResult
 * To be injected to the RestResult
 *
 * PHP version 5.4
 *
 * @category RESTFul_API
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */
class RestResponseResult
{

    /**
     * To hold response's metadata for time being
     * It might need to convert to an object itself
     *
     * @var array
     */
    protected $metadata = array(
        'status' => null,
        'code' => null,
        'model' => null,
        'criteria' => null,
        'start' => null,
        'size' => null,
    );

    protected $result = array();

    protected $method;

    /**
     * Constructore
     *
     * @param string     $method   the requested method
     * i.e. GET, POST
     * @param null|array $result   the result comming from model
     * @param null|array $metadata set of metadata
     *
     * @return null
     */
    public function __construct($method, $result = null, $metadata = null)
    {

        $this->setMetadataBasedOnMethod($method);

        if (!is_null($result)) {
            $this->result = $result;
        }

        if (!is_null($metadata)) {
            $this->metadata = $metadata;
        }
    }

    /**
     * Sets metadata's status
     *
     * @param string $status REST responce status e.g. 201, 200
     *
     * @return null
     */
    public function setStatus($status)
    {
        $this->metadata['status'] = $status;
    }

    /**
     * Gets metadata's status
     *
     * @return null
     */
    public function getStatus()
    {
        return $this->metadata['status'];
    }


    /**
     * Sets metadata's code
     *
     * @param string $code e.g. Success, Not Found
     *
     * @return null
     */
    public function setCode($code)
    {
        $this->metadata['code'] = $code;
    }

    /**
     * Gets metadata's Code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->metadata['code'];
    }

    /**
     * Sets metadata's model's name
     *
     * @param string $modelName the model name responding to the call
     *
     * @return null
     */
    public function setModel($modelName)
    {
        $this->metadata['model'] = $modelName;
    }

    /**
     * Gets metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Checks if we had a successful process
     *
     * @return bool
     */
    public function isSuccess()
    {

        $success = false;

        if (substr($this->getCode(), 0, 1) == 2) {
            $success = true;
        }

        return $success;
    }

    /**
     * Sets the main result based on provided model's dump
     *
     * @param array $data key/value pairs representing models data
     *
     * @return null
     */
    public function setResult(array $data)
    {
        $this->result = $data;
    }

    /**
     * Get the main result
     *
     * @param bool $jsonFormat If set true will convert the result
     * to a JSON string
     *
     * @return bool|string
     */
    public function getResult($jsonFormat = false)
    {
        return $jsonFormat ? json_encode($this->result) : $jsonFormat;
    }

    /**
     * To construct an array consist of metadata and main result
     *
     * @param bool $sortMetadataBykey If set will order the metadata fields by name
     *
     * @return array
     */
    public function toArray($sortMetadataBykey = false)
    {

        if ($sortMetadataBykey) {
            ksort($this->metadata);
        }

        return array(
            'metadata' => $this->metadata,
            'result' => $this->result
        );
    }

    /**
     * Sets metadata's criteria and criteria related fields
     * i.e. page size and starting page
     *
     * @param RestCriteria $criteria Used criteria to build this result
     *
     * @return null
     */
    public function setCriteria(RestCriteria $criteria)
    {
        $this->metadata['criteria'] = $criteria->getParams();
        $this->metadata['start'] = (int)$criteria->getLimit()['offset'];
        $this->metadata['size'] = (int)$criteria->getLimit()['number'];
    }

    /**
     * Sets the total number of existing results, and not just what
     * was fetched due to the specified criteria
     *
     * @param int $total number of the existing records
     *
     * @return null
     */
    public function setTotal($total)
    {
        $this->metadata['total'] = (int)$total;
    }

    /**
     * Sets the count of the fetched records only and not
     * the number of all available records
     *
     * @param int $count number of retrieved records
     *
     * @return null
     */
    public function setCount($count)
    {
        $this->metadata['count'] = (int)$count;
    }

    /**
     * This method will eliminate metadata's undesired fields
     *based on the requested RESTFul method e.g. GET, POST
     *
     * @param RestResponseResult $method An individual result
     *
     * @return null
     */
    public function setMetadataBasedOnMethod($method)
    {

        $this->method = $method;
        $metadataWhitelist = [];

        switch ($method) {
        case "GET":
            $metadataWhitelist = array('status',
                'code',
                'model',
                'criteria',
                'size',
                'size');
            break;
        case "POST":
            $metadataWhitelist = ['status', 'code', 'model'];
            break;
        case "PUT":
            break;
        case "DELETE":
            break;
        }

        foreach ($this->metadata as $key => $value) {
            if (!in_array($key, $metadataWhitelist)) {
                unset($this->metadata[$key]);
            }
        }
    }

    /**
     * Returns response method type e'g' POST, PUT
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

}
